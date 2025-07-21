<?php

namespace App\Filament\Resources;

use App\Enum\AnalysisStatus;
use App\Filament\Exports\RentalAnalysisExporter;
use App\Filament\Resources\RentalAnalysisResource\Form\RentalAnalysisResourceForm;
use App\Filament\Resources\RentalAnalysisResource\Pages;
use App\Models\RentalAnalysis;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class RentalAnalysisResource extends Resource
{
    protected static ?string $model = RentalAnalysis::class;

    protected static ?string $label = 'Análise de Aluguel';

    protected static ?string $pluralLabel = 'Análises de Aluguel';

    protected static ?string $navigationIcon = 'carbon-text-link-analysis';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                RentalAnalysisResourceForm::getTabs()
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('id')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('contract_number')
                    ->label('Número do contrato')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tenants.name')
                    ->label('Inquilino')
                    ->searchable()
                    ->limit(50)
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('realEstateAgent.property_agency')
                    ->label('Imobiliária')
                    ->limit(50)
                    ->searchable()
                    ->sortable(),

            ])
            ->defaultSort('id', 'desc')
            ->extremePaginationLinks()
            ->groups([

            ])
            ->filters([
                SelectFilter::make('status')
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->label('Status')
                    ->options(AnalysisStatus::class),

                Filter::make('contract_signature_date')
                    ->form([
                        DatePicker::make('contract_signature_date')
                            ->label('Assinado de')
                            ->format('Y-m-d')
                            ->displayFormat('d/m/Y')
                            ->native(false),
                        DatePicker::make('hora_saida')
                            ->label('Assinado até')
                            ->format('Y-m-d')
                            ->displayFormat('d/m/Y')
                            ->native(false),
                    ])
                    ->label('Data de Assinatura do Contrato')
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['contract_signature_date'], function (Builder $query, $date) {
                                $dateStartOfDay = Carbon::parse($date)
                                    ->startOfDay();

                                return $query->where('contract_signature_date', '>=', $dateStartOfDay);
                            })
                            ->when($data['hora_saida'], function (Builder $query, $date) {
                                $dateEndOfDay = Carbon::parse($date)
                                    ->endOfDay();

                                return $query->where('contract_signature_date', '<=', $dateEndOfDay);
                            });
                    }),


                Filter::make('contract_renewal_date')
                    ->form([
                        DatePicker::make('contract_renewal_date')
                            ->label('Renovado de')
                            ->format('Y-m-d')
                            ->displayFormat('d/m/Y')
                            ->native(false),
                        DatePicker::make('hora_saida')
                            ->label('Renovado até')
                            ->format('Y-m-d')
                            ->displayFormat('d/m/Y')
                            ->native(false),
                    ])
                    ->label('Data de Assinatura do Contrato')
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['contract_renewal_date'], function (Builder $query, $date) {
                                $dateStartOfDay = Carbon::parse($date)
                                    ->startOfDay();

                                return $query->where('contract_renewal_date', '>=', $dateStartOfDay);
                            })
                            ->when($data['hora_saida'], function (Builder $query, $date) {
                                $dateEndOfDay = Carbon::parse($date)
                                    ->endOfDay();

                                return $query->where('contract_renewal_date', '<=', $dateEndOfDay);
                            });
                    }),

            ],layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),

                ExportBulkAction::make()
                    ->icon('entypo-export')
                    ->exporter(RentalAnalysisExporter::class)
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AuditsRelationManager::class,
        ];
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $data['analyst_id'] = auth()->id();

        return $data;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRentalAnalyses::route('/'),
            'create' => Pages\CreateRentalAnalysis::route('/create'),
            'edit' => Pages\EditRentalAnalysis::route('/{record}/edit'),
        ];
    }
}
