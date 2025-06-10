<?php

namespace App\Filament\Resources\PropertyResource\Form;

use App\Enum\PropertyType;
use App\Enum\RentalStatus;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Leandrocfe\FilamentPtbrFormFields\Cep;

abstract class PropertyForm
{
    public static function getFormSchema(): array
    {
        return [

            Section::make()
                ->columns(5)
                ->schema([

                    Select::make('type')
                        ->label('Tipo')
                        ->options(PropertyType::class),

                    Select::make('status')
                        ->label('Status')
                        ->default(RentalStatus::UNAVAILABLE->value)
                        ->options(RentalStatus::class),

                    Cep::make('zip_code')
                        ->label('CEP')
                        ->required()
                        ->viaCep(
                            mode: 'suffix', // Determines whether the action should be appended to (suffix) or prepended to (prefix) the cep field, or not included at all (none).
                            errorMessage: 'CEP inválido.', // Error message to display if the CEP is invalid.

                            /**
                             * Other form fields that can be filled by ViaCep.
                             * The key is the name of the Filament input, and the value is the ViaCep attribute that corresponds to it.
                             * More information: https://viacep.com.br/
                             */
                            setFields: [
                                'street_address' => 'logradouro',
                                'number' => 'numero',
                                'complement' => 'complemento',
                                'neighborhood' => 'bairro',
                                'city' => 'localidade',
                                'state' => 'uf',
                            ]
                        ),

                    TextInput::make('street_address')
                        ->label('Endereço')
                        ->columnSpan(2)
                        ->visible(fn ($get) => filled($get('zip_code'))),

                    TextInput::make('number')
                        ->label('Número')
                        ->visible(fn ($get) => filled($get('zip_code'))),

                    TextInput::make('complement')
                        ->label('Complemento')
                        ->visible(fn ($get) => filled($get('zip_code'))),

                    TextInput::make('neighborhood')
                        ->label('Bairro')
                        ->visible(fn ($get) => filled($get('zip_code'))),

                    TextInput::make('city')
                        ->label('Cidade')
                        ->visible(fn ($get) => filled($get('zip_code'))),

                    TextInput::make('state')
                        ->label('Estado')
                        ->visible(fn ($get) => filled($get('zip_code'))),

                    Textarea::make('description')
                        ->label('Descrição')
                        ->columnSpanFull(),
                ]),
            Section::make()
                ->columns(5)
                ->schema([

                    TextInput::make('owner_name')
                        ->label('Proprietário')
                        ->columnSpan(2),

                    TextInput::make('owner_phone')
                        ->tel()
                        ->required()
                        ->label('Telefone')
                        ->mask('(99) 99999-9999')
                        ->placeholder('(00) 00000-0000'),

                    TextInput::make('owner_email')
                        ->columnSpan(2)
                        ->label('Email')
                        ->email()
                        ->required(),
                ])
        ];
    }
}
