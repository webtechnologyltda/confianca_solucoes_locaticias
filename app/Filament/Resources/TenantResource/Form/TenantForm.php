<?php

namespace App\Filament\Resources\TenantResource\Form;

use App\Enum\MaritalStatus;
use App\Enum\TenantStatus;
use Asmit\FilamentUpload\Enums\PdfViewFit;
use Asmit\FilamentUpload\Forms\Components\AdvancedFileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
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
                        ->unique(ignoreRecord: true)
                        ->validationAttribute('CPF')
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
            Section::make()
                ->columnSpanFull()
                ->schema([
                    Repeater::make('documents')
                        ->addActionLabel('Adicionar documento')
                        ->label('Documentos')
                        ->relationship('documents')
                        ->schema([
                            TextInput::make('name')->label('Nome do documento')->required(),

                            AdvancedFileUpload::make('path')
                                ->label('Anexo')
                                ->required()
                                ->downloadable()
                                ->directory('rental-analysis')
                                ->acceptedFileTypes(['application/pdf', 'image/*'])
                                ->maxFiles(5)
                                ->maxSize(2048) // 10MB
                                ->panelLayout('grid')
                                ->pdfDisplayPage(1)
                                ->pdfFitType(PdfViewFit::FITBH)
                                ->pdfPreviewHeight(320)
                                ->pdfZoomLevel(100)
                                ->pdfNavPanes(false)
                                ->openable()
                                ->multiple()
                                ->uploadingMessage('Carregando...')
                                ->previewable(true),

                        ]),
                ])
        ];
    }
}
