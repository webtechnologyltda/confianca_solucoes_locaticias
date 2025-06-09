<?php

namespace App\Filament\Resources\RentalAnalysisResource\Pages;

use App\Filament\Resources\RentalAnalysisResource;
use App\Filament\Resources\RentalAnalysisResource\Form\RentalAnalysisResourceForm;
use Filament\Resources\Pages\CreateRecord;

class CreateRentalAnalysis extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = RentalAnalysisResource::class;

    protected function getSteps()
    {
        return RentalAnalysisResourceForm::getSteps();
    }
}
