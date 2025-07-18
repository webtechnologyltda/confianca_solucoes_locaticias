<?php

namespace App\Filament\Resources;

use App\Filament\Exports\RealEstateAgentExporter;
use App\Filament\Resources\RealEstateAgentResource\Form\RealEstateAgentForm;
use App\Filament\Resources\RealEstateAgentResource\Pages;
use App\Models\RealEstateAgent;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Table;

class RealEstateAgentResource extends Resource
{
    protected static ?string $model = RealEstateAgent::class;

    protected static ?string $navigationIcon = 'fluentui-person-key-20';

    protected static ?string $label = 'Corretor';

    protected static ?string $pluralLabel = 'Corretores';

    protected static ?string $navigationGroup = 'Cadastros';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                RealEstateAgentForm::getFormSchema(),
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Código')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefone')
                    ->formatStateUsing(fn ($state) => phoneFormatAndCellPhone($state))
                    ->searchable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                ExportBulkAction::make()
                    ->icon('entypo-export')
                    ->exporter(RealEstateAgentExporter::class)
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRealEstateAgents::route('/'),
            'create' => Pages\CreateRealEstateAgent::route('/create'),
            'edit' => Pages\EditRealEstateAgent::route('/{record}/edit'),
        ];
    }
}
