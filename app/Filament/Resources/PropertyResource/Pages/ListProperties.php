<?php

namespace App\Filament\Resources\PropertyResource\Pages;

use App\Enum\RentalStatus;
use App\Filament\Resources\PropertyResource;
use App\Models\Property;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListProperties extends ListRecords
{
    protected static string $resource = PropertyResource::class;

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
                ->badge(Property::count()),

            RentalStatus::AVAILABLE->getLabel() => Tab::make()
                ->icon(RentalStatus::AVAILABLE->getIcon())
                ->iconSize(14)
                ->badge(Property::where('status', RentalStatus::AVAILABLE)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', RentalStatus::AVAILABLE)),

            RentalStatus::RESERVED->getLabel() => Tab::make()
                ->icon(RentalStatus::RESERVED->getIcon())
                ->iconSize(14)
                ->badge(Property::where('status', RentalStatus::RESERVED)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', RentalStatus::RESERVED)),

            RentalStatus::RENTED->getLabel() => Tab::make()
                ->icon(RentalStatus::RENTED->getIcon())
                ->iconSize(14)
                ->badge(Property::where('status', RentalStatus::RENTED)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', RentalStatus::RENTED)),

            RentalStatus::UNAVAILABLE->getLabel() => Tab::make()
                ->icon(RentalStatus::UNAVAILABLE->getIcon())
                ->iconSize(14)
                ->badge(Property::where('status', RentalStatus::UNAVAILABLE)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', RentalStatus::UNAVAILABLE)),

        ];
    }
}
