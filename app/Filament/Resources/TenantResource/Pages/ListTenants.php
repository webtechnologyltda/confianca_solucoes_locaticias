<?php

namespace App\Filament\Resources\TenantResource\Pages;

use App\Enum\TenantStatus;
use App\Filament\Resources\TenantResource;
use App\Models\Tenant;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListTenants extends ListRecords
{
    protected static string $resource = TenantResource::class;

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
                ->badge(Tenant::count()),

            TenantStatus::APPROVED->getLabel() => Tab::make()
                ->icon(TenantStatus::APPROVED->getIcon())
                ->iconSize(14)
                ->badge(Tenant::where('status', TenantStatus::APPROVED)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', TenantStatus::APPROVED)),

            TenantStatus::PENDING->getLabel() => Tab::make()
                ->icon(TenantStatus::PENDING->getIcon())
                ->iconSize(14)
                ->badge(Tenant::where('status', TenantStatus::PENDING)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', TenantStatus::PENDING)),

            TenantStatus::REJECTED->getLabel() => Tab::make()
                ->icon(TenantStatus::REJECTED->getIcon())
                ->iconSize(14)
                ->badge(Tenant::where('status', TenantStatus::REJECTED)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', TenantStatus::REJECTED)),
        ];
    }
}
