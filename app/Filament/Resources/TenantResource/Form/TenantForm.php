<?php

namespace App\Filament\Resources\TenantResource\Form;

use App\Enum\MaritalStatus;
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
                       ->label('Nome Completo')
                       ->required(),

                   Document::make('cpf')
                       ->required()
                       ->label('CPF')
                       ->cpf(),

                   DatePicker::make('birth_date')
                       ->label('Data de Nascimento'),

                   TextInput::make('email')
                       ->label('E-mail')
                       ->required()
                       ->email(),

                   TextInput::make('phone')
                       ->tel()
                       ->required()
                       ->label('Telefone')
                       ->mask('(99) 99999-9999')
                       ->placeholder('(00) 00000-0000'),

                   Money::make('monthly_income')
                       ->required()
                       ->label('Renda Mensal'),

                   TextInput::make('occupation')
                       ->label('Profissão'),

                   Select::make('marital_status')
                       ->options( MaritalStatus::class),

                   Textarea::make('additional_notes')
                       ->label('Observações')
                       ->columnSpanFull(),
               ])
             ];
    }

}
