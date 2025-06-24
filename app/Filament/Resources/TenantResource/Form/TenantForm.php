<?php

namespace App\Filament\Resources\TenantResource\Form;

use App\Enum\MaritalStatus;
use App\Enum\TenantStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Leandrocfe\FilamentPtbrFormFields\Document;
use Leandrocfe\FilamentPtbrFormFields\Money;

abstract class TenantForm
{
    public static function getFormSchema(): array
    {
        return [
            Section::make()
                ->columns(4)
                ->schema([
                    TextInput::make('name')
                        ->columnSpan(2)
                        ->label('Nome completo')
                        ->required(),

                    Document::make('cpf')
                        ->required()
                        ->label('CPF')
                        ->cpf('99999999999'),

                    DatePicker::make('birth_date')
                        ->label('Data de nascimento'),

                    TextInput::make('email')
                        ->label('E-mail')
                        ->columnSpan(2)
                        ->required()
                        ->email(),

                    TextInput::make('phone')
                        ->tel()
                        ->required()
                        ->label('Telefone')
                        ->mask('(99) 99999-9999')
                        ->placeholder('(00) 00000-0000'),

                    TextInput::make('occupation')
                        ->label('Profissão'),

                    Money::make('monthly_income')
                        ->required()
                        ->label('Renda mensal'),

                    Select::make('marital_status')
                        ->label('Estado civil')
                        ->options(MaritalStatus::class),

                    Select::make('status')
                        ->label('Status')
                        ->default(TenantStatus::APPROVED)
                        ->options(TenantStatus::class),

                    Textarea::make('additional_notes')
                        ->label('Observações')
                        ->columnSpanFull(),

                ]),
        ];
    }
}
