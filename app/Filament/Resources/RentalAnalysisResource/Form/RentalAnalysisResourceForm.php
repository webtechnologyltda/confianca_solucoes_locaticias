<?php

namespace App\Filament\Resources\RentalAnalysisResource\Form;

use App\Enum\AnalysisStatus;
use App\Enum\MaritalStatus;
use App\Enum\RentalStatus;
use App\Models\Audit;
use App\Models\Property;
use App\Models\RealEstateAgent;
use App\Models\Tenant;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Self_;
use function Laravel\Prompts\search;

abstract class RentalAnalysisResourceForm
{

    public static function getSteps():array
    {
        return [
            Step::make('Participante')
                ->schema(self::getFormSchemaTenent()),
            Step::make('Imovel')
                ->schema(
                    self::getFormSchemaProperty()
                ),
            Step::make('Analise')
                ->schema(
                    self::getFormSchemaAnalysis()
                ),

        ];
    }

    public static function getFormSchemaAnalysis(): array
    {
        return [
           Grid::make()
            ->columns(4)
            ->schema([


                Section::make('Análise')
                    ->columns(4)
                    ->schema([
                        Select::make('status')
                            ->label('Status da Análise')
                            ->options( AnalysisStatus::class)
                            ->default(AnalysisStatus::PENDING->value)
                            ->live()
                            ->required(),

                        TextInput::make('credit_score')
                            ->label('Score de Crédito')
                            ->numeric(),

                        DatePicker::make('analysis_date')
                            ->label('Data da Análise')
                            ->default(now()),

                        Hidden::make('analyst_id')
                            ->label('Analista')
                            ->default(auth()->id()),

                        Select::make('real_estate_agent_id')
                            ->reactive()
                            ->relationship(name: 'realEstateAgent', titleAttribute: 'name')
                            ->label('Corretor'),

                        Placeholder::make('real_estate_agent_phone')
                            ->label('Telefone corretor')
                            ->content(function (callable $get) {
                                $realEstateAgentId = $get('real_estate_agent_id');

                                if (!$realEstateAgentId) {
                                    return 'Selecione um corretor';
                                }

                                $realEstateAgent = RealEstateAgent::find($realEstateAgentId);

                                return $realEstateAgent?->phone ?? 'Corretor não cadastrado';
                            }),



                ]),
                Section::make('Financeiro')
                    ->columns(4)
                    ->schema([

                        TextInput::make('house_rental_value')
                            ->label('Valor do Aluguel')
                            ->required()
                            ->numeric(),

                        TextInput::make('other_tax')
                            ->label('Outras Taxas')
                            ->numeric(),

                        TextInput::make('tax')
                            ->label('Taxa')
                            ->numeric()
                            ->default(null)
                            ->required()
                            ->hidden(fn (Get $get) => $get('status') != AnalysisStatus::APPROVED->value),
                    ])
            ])
        ];
    }


    public static function getFormSchemaTenent(): array
    {

        return [

            Repeater::make( 'rental_analysis_tenant_id')
                ->relationship('rentalAnalysisTenants')
                ->minItems(1)
                ->maxItems(2)
                ->columns(2)
                ->schema([
                    Select::make('tenant_id')
                        ->label('Inquilino')
                        ->preload()
                        ->live()
                        ->optionsLimit(10)
                        ->getOptionLabelFromRecordUsing(fn (Model $record) => $record->name . ' - ' . cpfFormat($record->cpf))
                        ->required()
                        ->relationship(name: 'tenant', titleAttribute: 'name')
                        ->searchable(['name', 'cpf',]),

                    Placeholder::make('cpf_tenant')
                        ->label('CPF')
                        ->content(function (callable $get) {
                            $tenantId = $get('tenant_id');

                            if (!$tenantId) {
                                return 'Selecione um Inquilino';
                            }

                            $tenant = Tenant::find($tenantId);

                            return cpfFormat($tenant?->cpf) ?? 'CPF não cadastrado';
                        }),

                    Placeholder::make('monthly_income')
                        ->label('Renda Mensal')
                        ->content(function (callable $get) {
                            $tenantId = $get('tenant_id');

                            if (!$tenantId) {
                                return 'Selecione um Inquilino';
                            }

                            $tenant = Tenant::find($tenantId);

                            return brazilianMoneyFormat($tenant?->monthly_income) ?? 'Renda não cadastrada';
                        }),

                    Placeholder::make('phone_tenant')
                        ->label('Telefone')
                        ->content(function (callable $get) {
                            $tenantId = $get('tenant_id');

                            if (!$tenantId) {
                                return 'Selecione um Inquilino';
                            }

                            $tenant = Tenant::find($tenantId);

                            return phoneFormatAndCellPhone($tenant?->phone) ?? 'Telefone não cadastrado';
                        }),
                ]),
        ];
    }

    public static function getFormSchemaProperty(): array
    {
        return [
            Grid::make()
                ->columns(4)
                ->schema([
                   Section::make('Imóvel')
                       ->label('Imovel')
                       ->columns(4)
                       ->schema([
                           Select::make('property_id')
                               ->relationship(name: 'property',
                                   titleAttribute: 'id',
                                   modifyQueryUsing: fn ($query) => Property::available($query))
                               ->label('Codigo do Imóvel')
                               ->reactive()
                               ->required(),

                           Placeholder::make('property_type')
                               ->label('Tipo de Imóvel')
                               ->content(function (callable $get) {
                                   $propertyId = $get('property_id');

                                   if (!$propertyId) {
                                       return 'Selecione um imóvel';
                                   }

                                   $property = \App\Models\Property::find($propertyId);

                                   return $property?->type ?? 'Tipo não cadastrado';
                               }),

                           Placeholder::make('property_address')
                               ->label('Endereço')
                               ->content(function (callable $get) {
                                   $propertyId = $get('property_id');

                                   if (!$propertyId) {
                                       return 'Selecione um imóvel';
                                   }

                                   $property = \App\Models\Property::find($propertyId);

                                   return $property?->street_address ?? 'Endereço não cadastrado';
                               }),

                           Placeholder::make('property_number')
                               ->label('Numero')
                               ->content(function (callable $get) {
                                   $propertyId = $get('property_id');

                                   if (!$propertyId) {
                                       return 'Selecione um imóvel';
                                   }

                                   $property = \App\Models\Property::find($propertyId);

                                   return $property?->number ?? 'Numero não cadastrado';
                               }),

                           Placeholder::make('property_neighborhood')
                               ->label('Bairro')
                               ->content(function (callable $get) {
                                   $propertyId = $get('property_id');

                                   if (!$propertyId) {
                                       return 'Selecione um imóvel';
                                   }

                                   $property = \App\Models\Property::find($propertyId);

                                   return $property?->neighborhood ?? 'Bairro não cadastrado';
                               }),

                           Placeholder::make('property_city')
                               ->label('Cidade')
                               ->content(function (callable $get) {
                                   $propertyId = $get('property_id');

                                   if (!$propertyId) {
                                       return 'Selecione um imóvel';
                                   }

                                   $property = \App\Models\Property::find($propertyId);

                                   return $property?->city ?? 'Cidade não cadastrado';
                               }),

                           Placeholder::make('property_state')
                               ->label('Estado')
                               ->content(function (callable $get) {
                                   $propertyId = $get('property_id');

                                   if (!$propertyId) {
                                       return 'Selecione um imóvel';
                                   }

                                   $property = \App\Models\Property::find($propertyId);

                                   return $property?->state ?? 'Cidade não cadastrado';
                               }),
                       ]),

                    Section::make('Proprietario')
                        ->label('Proprietario')
                        ->columns(3)
                        ->schema([
                            Placeholder::make('property_owner_name')
                                ->label('Proprietario')
                                ->content(function (callable $get) {
                                    $propertyId = $get('property_id');

                                    if (!$propertyId) {
                                        return 'Selecione um imóvel';
                                    }

                                    $property = \App\Models\Property::find($propertyId);

                                    return $property?->owner_name ?? 'Proprietario não cadastrado';
                                }),

                            Placeholder::make('property_owner_phone')
                                ->label('E-mail Proprietario')
                                ->content(function (callable $get) {
                                    $propertyId = $get('property_id');

                                    if (!$propertyId) {
                                        return 'Selecione um imóvel';
                                    }

                                    $property = \App\Models\Property::find($propertyId);

                                    return $property?->owner_phone ?? 'Proprietario não cadastrado';
                                }),

                            Placeholder::make('property_owner_email')
                                ->label('Telefone Proprietario')
                                ->content(function (callable $get) {
                                    $propertyId = $get('property_id');

                                    if (!$propertyId) {
                                        return 'Selecione um imóvel';
                                    }

                                    $property = \App\Models\Property::find($propertyId);

                                    return $property?->owner_email ?? 'Proprietario não cadastrado';
                                }),

                    ])
            ])

        ];
    }

}
