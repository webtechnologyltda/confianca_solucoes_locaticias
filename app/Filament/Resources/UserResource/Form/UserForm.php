<?php

namespace App\Filament\Resources\UserResource\Form;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

abstract class UserForm
{

    public static function getFormSchema() {
        return [
           Section::make()
               ->columns(2)
               ->schema([
                   TextInput::make('name')
                       ->required()
                       ->label('Name'),

                   TextInput::make('email')
                       ->label('Email')
                       ->email()
                       ->required()
                       ->maxLength(255),

                   TextInput::make('password')
                       ->label('Senha')
                       ->password()
                   ->revealable(true)
                       ->required()
                       ->validationMessages([
                           'confirmed' => 'A confirmação da senha não confere.',
                           'min' => 'A senha deve ter pelo menos 8 caracteres.',
                       ])
                       ->rules(['confirmed'])
                       ->required(fn (string $operation) => $operation === 'create'),


                   TextInput::make('password_confirmation')
                       ->label('Confirmar Senha')
                       ->password()
                       ->revealable(true)
                       ->visible(fn ($get) => filled($get('password')))
                       ->required(fn ($get) => filled($get('password'))),


                   Select::make('roles')
                       ->label('Perfis')
                       ->relationship('roles', 'name')
                       ->multiple()
                       ->preload()
                       ->searchable(),
               ])
        ];
    }

}
