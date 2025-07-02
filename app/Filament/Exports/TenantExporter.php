<?php

namespace App\Filament\Exports;

use App\Models\Tenant;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\CellVerticalAlignment;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;

class TenantExporter extends Exporter
{
    protected static ?string $model = Tenant::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('Código'),

            ExportColumn::make('name')
                ->label('Nome'),

            ExportColumn::make('cpf')
                ->label('CPF')
                ->enabledByDefault(false)
                ->formatStateUsing(fn ($state) => cpfFormat($state)),

            ExportColumn::make('status')
                ->label('Status')
                ->formatStateUsing(fn ($state) => $state->getLabel()),

            ExportColumn::make('birth_date')
                ->label('Data de Nascimento')
                ->enabledByDefault(false)
                ->formatStateUsing(fn ($state) => $state?->format('d/m/Y')),

            ExportColumn::make('email')
                ->enabledByDefault(false)
                ->label('E-mail'),

            ExportColumn::make('phone')
                ->label('Telefone')
                ->enabledByDefault(false)
                ->formatStateUsing(fn ($state) => phoneFormatAndCellPhone($state)),

            ExportColumn::make('monthly_income')
                ->label('Renda Mensal')
                ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.')),

            ExportColumn::make('occupation')
                ->label('Ocupação'),

            ExportColumn::make('marital_status')
                ->label('Estado Civil')
                ->formatStateUsing(fn ($state) => $state->getLabel()),

            ExportColumn::make('additional_notes')
                ->label('Observações Adicionais'),

            ExportColumn::make('created_at')
                ->label('Criado em')
                ->formatStateUsing(fn ($state) => $state?->format('d/m/Y H:i:s')),

            ExportColumn::make('updated_at')
                ->label('Atualizado em')
                ->formatStateUsing(fn ($state) => $state?->format('d/m/Y H:i:s'))
        ];
    }

    public function getXlsxHeaderCellStyle(): ?Style
    {
        return (new Style)
            ->setFontBold()
            ->setFontItalic()
            ->setFontSize(12)
            ->setFontName('Arial')
            ->setFontColor(Color::rgb(255, 255, 255))
            ->setBackgroundColor(Color::rgb(0, 0, 0))
            ->setCellAlignment(CellAlignment::CENTER)
            ->setCellVerticalAlignment(CellVerticalAlignment::CENTER);
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'A exportação do seu inquilino foi concluída e ' . number_format($export->successful_rows) . ' ' . str('linha foi exportada.')->plural($export->successful_rows);

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('linha falhou ao exportar.')->plural($failedRowsCount);
        }

        return $body;
    }
}
