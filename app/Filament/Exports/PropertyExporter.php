<?php

namespace App\Filament\Exports;

use App\Models\Property;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\CellVerticalAlignment;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;

class PropertyExporter extends Exporter
{
    protected static ?string $model = Property::class;

    public static function getColumns(): array
    {
        return [

            ExportColumn::make('id')
                ->label('')
                ->label('ID'),

            ExportColumn::make('street_address')
                ->label('Logradouro'),

            ExportColumn::make('number')
                ->label('Número'),

                ExportColumn::make('complement')
                ->label('Complemento'),

                ExportColumn::make('city')
                ->label('Cidade'),

                ExportColumn::make('neighborhood')
                ->label('Bairro'),

                ExportColumn::make('state')
                ->label('UF'),

                ExportColumn::make('zip_code')
                ->label('CEP'),

                ExportColumn::make('status')
                ->label('Status'),

                ExportColumn::make('description')
                ->label('Descrição'),

                ExportColumn::make('type')
                ->label('Tipo'),

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
        $body = 'Your property export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
