<?php

namespace App\Filament\Resources\RentalAnalysisResource\Form;

use App\Enum\AnalysisStatus;
use App\Enum\IndiceReantalAnalysis;
use App\Enum\PropertyType;
use App\Enum\TenantStatus;
use App\Models\Property;
use App\Models\RealEstateAgent;
use App\Models\Tenant;
use Asmit\FilamentUpload\Enums\PdfViewFit;
use Asmit\FilamentUpload\Forms\Components\AdvancedFileUpload;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Leandrocfe\FilamentPtbrFormFields\Money;

abstract class RentalAnalysisResourceForm
{
    public static function getSteps(): array
    {
        return [
            Step::make('Participante')
                ->schema(self::getFormSchemaTenent()),
            Step::make('Imovel')
                ->schema(
                    self::getFormSchemaProperty()
                ),
            Step::make('Análise')
                ->schema(
                    self::getFormSchemaAnalysis()
                ),
            Step::make('Documentos')
                ->schema(
                    self::getFormSchemaDocuments()
                ),

        ];
    }

    public static function getTabs(): array
    {
        return [
            Tabs::make()->tabs([
               Tab::make('Participante')->schema( self::getFormSchemaTenent())->icon( 'fas-user'),
               Tab::make('Imóvel')->schema( self::getFormSchemaProperty())->icon( 'fas-home'),
               Tab::make('Análise')->schema( self::getFormSchemaAnalysis())->icon( 'fas-chart-line'),
               Tab::make('Documentos')->schema( self::getFormSchemaDocuments())->icon('fas-file'),
            ])->columnSpanFull(),
        ];
    }

    public static function getFormSchemaDocuments(): array
    {
        return [
            Repeater::make('documents')
                ->relationship('documents')
                ->schema([
                    TextInput::make('name')->label('Nome do documento'),

                    AdvancedFileUpload::make('path')
                        ->label('Documento')
                        ->required()
                        ->downloadable()
                        ->directory('rental-analysis')
                        ->acceptedFileTypes(['application/pdf', 'image/*'])
                        ->maxFiles(5)
                        ->maxSize(10240) // 10MB
                        ->panelLayout('grid')
                        ->pdfDisplayPage(1)
                        ->pdfFitType(PdfViewFit::FITBH)
                        ->pdfPreviewHeight(320)
                        ->pdfZoomLevel(100)
                        ->pdfNavPanes(false)
                        ->openable()
                        ->multiple()
                        ->maxSize(2048)
                        ->uploadingMessage('Carregando...')
                        ->previewable(true),

                ]),
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
                                ->options(AnalysisStatus::class)
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
                                ->default(auth()->id()),

                            Placeholder::make('analyst_name')
                                ->label('Analista')
                                ->content(fn () => auth()->user()->name),

                            Select::make('real_estate_agent_id')
                                ->columnSpan([
                                    'default' => 1,
                                    'lg' => 2,
                                ])
                                ->searchable()
                                ->preload()
                                ->reactive()
                                ->relationship(name: 'realEstateAgent', titleAttribute: 'name')
                                ->required()
                                ->label('Corretor'),

                            Placeholder::make('real_estate_agent_phone')
                                ->label('Telefone corretor')
                                ->content(function (callable $get) {
                                    $realEstateAgentId = $get('real_estate_agent_id');

                                    if (! $realEstateAgentId) {
                                        return 'Selecione um corretor';
                                    }

                                    $realEstateAgent = RealEstateAgent::find($realEstateAgentId);

                                    return $realEstateAgent?->phone ?? 'Corretor não cadastrado';
                                }),

                        ]),
                    Section::make('Financeiro')
                        ->columns(4)
                        ->schema([

                            Money::make('house_rental_value')
                                ->label('Valor do Aluguel')
                                ->required()
                                ->reactive()
                                ->intFormat()
                                ->prefix('R$'),

                            Money::make('other_tax')
                                ->label('Taxas')
                                ->prefix('R$')
                                ->intFormat()
                                ->reactive(),

                            Money::make('tax')
                                ->label('Taxa')
                                ->suffix('%')
                                ->intFormat()
                                ->reactive()
                                ->default(null)
                                ->required()
                                ->hidden(fn (Get $get) => $get('status') != AnalysisStatus::APPROVED->value),

                            Radio::make('indice')
                                ->label(' Índice')
                                ->options(IndiceReantalAnalysis::class)->default(IndiceReantalAnalysis::IC->value)
                                ->hidden(fn (Get $get) => $get('status') != AnalysisStatus::APPROVED->value),

                            Placeholder::make('total_value_month')
                                ->label('Total mês')
                                ->content(fn (Get $get) => calculateRentalAnalysisMonth($get('tax'), $get('other_tax'), $get('house_rental_value'))
                                ),
                            Placeholder::make('total_value_year')
                                ->label('Total ano')
                                ->content(fn (Get $get) => calculateRentalAnalysisYear($get('tax'), $get('other_tax'), $get('house_rental_value'))
                                ),
                        ]),
                ]),
        ];
    }

    public static function getFormSchemaTenent(): array
    {

        return [

            Repeater::make('rental_analysis_tenant_id')
                ->relationship('rentalAnalysisTenants')
                ->label('')
                ->addActionLabel('Adicionar Inquilino')
                ->minItems(1)
                ->maxItems(2)
                ->columns(2)
                ->schema([
                    Select::make('tenant_id')
                        ->label('Inquilino')
                        ->preload()
                        ->fixIndistinctState()
                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                        ->optionsLimit(10)
                        ->getOptionLabelFromRecordUsing(fn (Model $record) => $record->name.' - '.cpfFormat($record->cpf))
                        ->required()
                        ->relationship(name: 'tenant', titleAttribute: 'name')
                        ->searchable(['name', 'cpf']),
//                        ->afterStateUpdated(function (Set $set, $state,$livewire) {
//                            if (! $state) {
//                                return;
//                            }
//                            $tenant = Tenant::find($state);
//                            if ($tenant && $tenant->status == TenantStatus::REJECTED->value) {
//                                $livewire->dispatch('open-modal', id: 'confirm-tentant');
//                            }
//                        }),


                    Placeholder::make('cpf_tenant')
                        ->label('CPF')
                        ->content(function (callable $get) {
                            $tenantId = $get('tenant_id');

                            if (! $tenantId) {
                                return 'Selecione um Inquilino';
                            }

                            $tenant = Tenant::find($tenantId);

                            return cpfFormat($tenant?->cpf) ?? 'CPF não cadastrado';
                        }),

                    Placeholder::make('monthly_income')
                        ->label('Renda Mensal')
                        ->content(function (callable $get) {
                            $tenantId = $get('tenant_id');

                            if (! $tenantId) {
                                return 'Selecione um Inquilino';
                            }

                            $tenant = Tenant::find($tenantId);

                            return brazilianMoneyFormat($tenant?->monthly_income) ?? 'Renda não cadastrada';
                        }),

                    Placeholder::make('phone_tenant')
                        ->label('Telefone')
                        ->content(function (callable $get) {
                            $tenantId = $get('tenant_id');

                            if (! $tenantId) {
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

                                    if (! $propertyId) {
                                        return 'Selecione um imóvel';
                                    }

                                    $property = \App\Models\Property::find($propertyId);

                                    return PropertyType::getType($property?->type) ?? 'Tipo não cadastrado';
                                }),

                            Placeholder::make('property_address')
                                ->label('Endereço')
                                ->content(function (callable $get) {
                                    $propertyId = $get('property_id');

                                    if (! $propertyId) {
                                        return 'Selecione um imóvel';
                                    }

                                    $property = \App\Models\Property::find($propertyId);

                                    return $property?->street_address ?? 'Endereço não cadastrado';
                                }),

                            Placeholder::make('property_number')
                                ->label('Numero')
                                ->content(function (callable $get) {
                                    $propertyId = $get('property_id');

                                    if (! $propertyId) {
                                        return 'Selecione um imóvel';
                                    }

                                    $property = \App\Models\Property::find($propertyId);

                                    return $property?->number ?? 'Numero não cadastrado';
                                }),

                            Placeholder::make('property_neighborhood')
                                ->label('Bairro')
                                ->content(function (callable $get) {
                                    $propertyId = $get('property_id');

                                    if (! $propertyId) {
                                        return 'Selecione um imóvel';
                                    }

                                    $property = \App\Models\Property::find($propertyId);

                                    return $property?->neighborhood ?? 'Bairro não cadastrado';
                                }),

                            Placeholder::make('property_city')
                                ->label('Cidade')
                                ->content(function (callable $get) {
                                    $propertyId = $get('property_id');

                                    if (! $propertyId) {
                                        return 'Selecione um imóvel';
                                    }

                                    $property = \App\Models\Property::find($propertyId);

                                    return $property?->city ?? 'Cidade não cadastrado';
                                }),

                            Placeholder::make('property_state')
                                ->label('Estado')
                                ->content(function (callable $get) {
                                    $propertyId = $get('property_id');

                                    if (! $propertyId) {
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

                                    if (! $propertyId) {
                                        return 'Selecione um imóvel';
                                    }

                                    $property = \App\Models\Property::find($propertyId);

                                    return $property?->owner_name ?? 'Proprietario não cadastrado';
                                }),

                            Placeholder::make('property_owner_phone')
                                ->label('E-mail Proprietario')
                                ->content(function (callable $get) {
                                    $propertyId = $get('property_id');

                                    if (! $propertyId) {
                                        return 'Selecione um imóvel';
                                    }

                                    $property = \App\Models\Property::find($propertyId);

                                    return $property?->owner_phone ?? 'Proprietario não cadastrado';
                                }),

                            Placeholder::make('property_owner_email')
                                ->label('Telefone Proprietario')
                                ->content(function (callable $get) {
                                    $propertyId = $get('property_id');

                                    if (! $propertyId) {
                                        return 'Selecione um imóvel';
                                    }

                                    $property = \App\Models\Property::find($propertyId);

                                    return $property?->owner_email ?? 'Proprietario não cadastrado';
                                }),

                        ]),
                ]),

        ];
    }
}
