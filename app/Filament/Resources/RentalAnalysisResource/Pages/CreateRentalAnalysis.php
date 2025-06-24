<?php

namespace App\Filament\Resources\RentalAnalysisResource\Pages;

use App\Filament\Resources\RentalAnalysisResource;
use App\Filament\Resources\RentalAnalysisResource\Form\RentalAnalysisResourceForm;
use App\Filament\Resources\RentalAnalysisResource\Traits\RentalAnalysisActions;
use Filament\Resources\Pages\CreateRecord;

class CreateRentalAnalysis extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;
    use RentalAnalysisActions;

    protected static string $resource = RentalAnalysisResource::class;

    protected function getSteps()
    {
        return RentalAnalysisResourceForm::getSteps();
    }
}
