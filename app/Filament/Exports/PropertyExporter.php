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
                ->label('Código'),

            ExportColumn::make('description')
                ->label('Descrição'),

            ExportColumn::make('type')
                ->label('Tipo'),

            ExportColumn::make('zip_code')
                ->enabledByDefault(false)
                ->label('CEP'),

            ExportColumn::make('street_address')
                ->enabledByDefault(false)
                ->label('Logradouro'),

            ExportColumn::make('number')
                ->enabledByDefault(false)
                ->label('Número'),

            ExportColumn::make('complement')
                ->enabledByDefault(false)
                ->label('Complemento'),

            ExportColumn::make('city')
                ->label('Cidade'),

            ExportColumn::make('neighborhood')
                ->enabledByDefault(false)
                ->label('Bairro'),

            ExportColumn::make('state')
                ->enabledByDefault(false)
                ->label('UF'),

            ExportColumn::make('status')
                ->formatStateUsing(fn ($state) => $state?->getLabel() ?? 'Não informado')
                ->label('Status'),

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
        $body = 'A exportação de imóveis foi concluída e ' . number_format($export->successful_rows) . ' ' . ($export->successful_rows > 1 ? 'linhas foram exportadas.' : 'linha foi exportada.');

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . ($failedRowsCount > 1 ? 'linhas falharam ao exportar.' : 'linha falhou ao exportar.');
        }

        return $body;
    }
}
