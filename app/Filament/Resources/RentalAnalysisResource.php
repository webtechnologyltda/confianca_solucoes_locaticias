<?php

namespace App\Filament\Resources;

use App\Enum\AnalysisStatus;
use App\Filament\Resources\RentalAnalysisResource\Form\RentalAnalysisResourceForm;
use App\Filament\Resources\RentalAnalysisResource\Pages;
use App\Models\RentalAnalysis;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
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
                    ->limit( 50 )
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
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
