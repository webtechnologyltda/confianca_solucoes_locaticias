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
                ->label('ID'),
            ExportColumn::make('property.id'),
            ExportColumn::make('status'),
            ExportColumn::make('credit_score'),
            ExportColumn::make('tax'),
            ExportColumn::make('other_tax'),
            ExportColumn::make('house_rental_value'),
            ExportColumn::make('observations'),
            ExportColumn::make('analysis_date'),
            ExportColumn::make('analyst.name'),
            ExportColumn::make('realEstateAgent.name'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('deleted_at'),
            ExportColumn::make('indice'),
            ExportColumn::make('contract_number'),
            ExportColumn::make('discount_month'),
            ExportColumn::make('discount_year'),
            ExportColumn::make('has_manual_discount'),
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
        $body = 'Your rental analysis export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
