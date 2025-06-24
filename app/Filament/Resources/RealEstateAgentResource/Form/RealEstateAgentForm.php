<?php

namespace App\Filament\Resources\RealEstateAgentResource\Form;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;

abstract class RealEstateAgentForm
{
    public static function getFormSchema(): array
    {
        return [
            Section::make()
                ->columns(4)
                ->schema([
                    TextInput::make('name')
                        ->columnSpan(2)
                        ->label('Nome Completo')
                        ->required(),

                    TextInput::make('property_agency')
                        ->columnSpan(2)
                        ->label('Imobiliária ')
                        ->required(),

                    TextInput::make('email')
                        ->label('E-mail')
                        ->unique()
                        ->columnSpan(2)
                        ->email()
                        ->maxLength(255),

                    TextInput::make('phone')
                        ->tel()
                        ->label('Telefone')
                        ->mask('(99) 99999-9999')
                        ->placeholder('(00) 00000-0000'),

                ]),

        ];
    }
}
