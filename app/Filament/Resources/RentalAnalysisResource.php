<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RentalAnalysisResource\Form\RentalAnalysisResourceForm;
use App\Filament\Resources\RentalAnalysisResource\Pages;
use App\Models\RentalAnalysis;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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
            ->schema([
                Wizard::make()
                    ->columnSpanFull()
                    ->schema(
                    RentalAnalysisResourceForm::getSteps()
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tenant.name')
                    ->numeric()
                    ->label('Proprietário')
                    ->sortable(),
                Tables\Columns\TextColumn::make('property.id')
                    ->numeric()
                    ->label('Cód. Imóvel')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('credit_score')
                    ->numeric()
                    ->label('Score de Crédito')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('analysis_date')
                    ->dateTime('d/m/Y H:i:s')
                    ->alignCenter()
                    ->label('Data da Análise')
                    ->sortable(),
                Tables\Columns\TextColumn::make('analyst.name')
                    ->alignCenter()
                    ->label('Analista')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime('d/m/Y H:i:s')
                    ->label('Data de Exclusão')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i:s')
                    ->label('Data de Criação')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d/m/Y H:i:s')
                    ->label('Data de Alteração')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
