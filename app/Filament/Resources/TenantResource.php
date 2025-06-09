<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TenantResource\Form\TenantForm;
use App\Filament\Resources\TenantResource\Pages;
use App\Models\Tenant;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static ?string $navigationIcon = 'fluentui-person-money-20';

    protected static ?string $label = 'Inquilino';

    protected static ?string $pluralLabel = 'Inquilinos';

    protected static ?string $navigationGroup = 'Cadastros';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(TenantForm::getFormSchema());
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

                Tables\Columns\TextColumn::make('cpf')
                    ->label('CPF')
                    ->formatStateUsing(fn ($state) => cpfFormat($state))
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefone')
                    ->formatStateUsing(fn ($state) => phoneFormatAndCellPhone($state))
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([

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
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }
}
