<?php

namespace App\Filament\Resources\RealEstateAgentResource\Pages;

use App\Filament\Resources\RealEstateAgentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRealEstateAgent extends EditRecord
{
    protected static string $resource = RealEstateAgentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
