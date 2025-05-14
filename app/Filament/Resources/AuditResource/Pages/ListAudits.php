<?php

namespace App\Filament\Resources\AuditResource\Pages;

use App\Filament\Resources\AuditResource;
use App\Models\Audit;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListAudits extends ListRecords
{
    protected static string $resource = AuditResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    public function getTabs(): array
    {
        return [
            'Todos' => Tab::make()
                ->icon('eos-all-inclusive')
                ->badge(Audit::count()),
            'Criados' => Tab::make()
                ->icon('eos-new-releases')
                ->badge(Audit::where('event', 'created')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('event', '=', 'created')),
            'Atualizados' => Tab::make()
                ->icon('fas-edit')
                ->badge(Audit::where('event', 'updated')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('event', '=', 'updated')),
            'Deletados' => Tab::make()
                ->icon('monoicon-delete')
                ->badge(Audit::where('event', 'deleted')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('event', '=', 'deleted')),
        ];
    }
}
