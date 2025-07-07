<?php

namespace App\Filament\Exports;

use App\Models\RentalAnalysis;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\CellVerticalAlignment;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;

class RentalAnalysisExporter extends Exporter
{
    protected static ?string $model = RentalAnalysis::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('Código'),

            ExportColumn::make('contract_number')
                ->enabledByDefault(false)
                ->label('Número do Contrato'),

            ExportColumn::make('property.id')
                ->label('Código do Imóvel'),

            ExportColumn::make('property.type')
                ->label('Tipo do Imóvel'),

            ExportColumn::make('status')
                ->formatStateUsing(fn ($state) => $state->getLabel())
                ->label('Status'),

            ExportColumn::make('credit_score')
                ->enabledByDefault(false)
                ->label('Score de Crédito'),

            ExportColumn::make('tax')
                ->label('Taxa'),

            ExportColumn::make('other_tax')
                ->label('Outras Taxas'),

            ExportColumn::make('house_rental_value')
                ->label('Valor do Aluguel'),

            ExportColumn::make('observations')
                ->label('Observações'),

            ExportColumn::make('analysis_date')
                ->label('Data da Análise')
                ->formatStateUsing(fn ($state) => $state?->format('d/m/Y H:i:s')),

            ExportColumn::make('analyst.name')
                ->label('Analista'),

            ExportColumn::make('realEstateAgent.name')
                ->label('Corretor'),

            ExportColumn::make('indice')
                ->formatStateUsing(fn ($state) => $state->getLabel())
                ->label('Índice'),

            ExportColumn::make('discount_month')
                ->enabledByDefault(false)
                ->label('Desconto Mensal'),

            ExportColumn::make('discount_year')
                ->enabledByDefault(false)
                ->label('Desconto Anual'),

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
        $body = 'A exportação de análises de aluguel foi concluída e ' . number_format($export->successful_rows) . ' ' . ($export->successful_rows > 1 ? 'linhas foram exportadas.' : 'linha foi exportada.');

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . ($failedRowsCount > 1 ? 'linhas falharam ao exportar.' : 'linha falhou ao exportar.');
        }

        return $body;
    }
}
