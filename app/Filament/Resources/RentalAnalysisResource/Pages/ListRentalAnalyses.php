<?php

namespace App\Filament\Resources\RentalAnalysisResource\Pages;

use App\Enum\AnalysisStatus;
use App\Filament\Resources\RentalAnalysisResource;
use App\Models\RentalAnalysis;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListRentalAnalyses extends ListRecords
{
    protected static string $resource = RentalAnalysisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Todos' => Tab::make()
                ->icon('eos-all-inclusive')
                ->badge(RentalAnalysis::count()),
            AnalysisStatus::PENDING->getLabel() => Tab::make()
                ->icon(AnalysisStatus::PENDING->getIcon())
                ->badge(RentalAnalysis::where('status', AnalysisStatus::PENDING)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', AnalysisStatus::PENDING)),


            AnalysisStatus::APPROVED->getLabel() => Tab::make()
                ->icon(AnalysisStatus::APPROVED->getIcon())
                ->badge(RentalAnalysis::where('status', AnalysisStatus::APPROVED)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', AnalysisStatus::APPROVED)),


            AnalysisStatus::REJECTED->getLabel() => Tab::make()
                ->icon(AnalysisStatus::REJECTED->getIcon())
                ->badge(RentalAnalysis::where('status', AnalysisStatus::REJECTED)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', AnalysisStatus::REJECTED)),
        ];

    }
}
