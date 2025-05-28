<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyResource\Pages;
use App\Models\Property;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'bi-houses-fill';

    protected static ?string $label = 'Imóvel';

    protected static ?string $pluralLabel = 'Imóveis';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('street_address')
                    ->maxLength(255),
                Forms\Components\TextInput::make('number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('complement')
                    ->maxLength(255),
                Forms\Components\TextInput::make('city')
                    ->maxLength(255),
                Forms\Components\TextInput::make('state')
                    ->maxLength(255),
                Forms\Components\TextInput::make('zip_code')
                    ->maxLength(255),
                Forms\Components\TextInput::make('rental_price')
                    ->numeric(),
                Forms\Components\TextInput::make('condo_fee')
                    ->numeric(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('street_address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('complement')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->searchable(),
                Tables\Columns\TextColumn::make('state')
                    ->searchable(),
                Tables\Columns\TextColumn::make('zip_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rental_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('condo_fee')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }
}
