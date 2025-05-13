<?php

namespace App\Filament\Resources\RentalAnalysisResource\Pages;

use App\Filament\Resources\RentalAnalysisResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRentalAnalysis extends EditRecord
{
    protected static string $resource = RentalAnalysisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
