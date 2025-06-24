<?php

namespace App\Filament\Resources\RentalAnalysisResource\Traits;

use App\Enum\TenantStatus;
use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\HtmlString;
use Livewire\Component;

trait RentalAnalysisActions
{
    public function showTenantNoticeModal()
    {
        return Action::make('showTenantNoticeModal')
            ->label('Inquilino '.TenantStatus::REJECTED->getLabel())
            ->icon(TenantStatus::REJECTED->getIcon())
            ->color(TenantStatus::REJECTED->getColor())
            ->modalHeading('Inquilino '.TenantStatus::REJECTED->getLabel())
            ->modalContent(new HtmlString('<span class="text-center">O inquilino selecionado está com o status '
                .strtolower(TenantStatus::REJECTED->getLabel())
                .'. </br>Você tem certeza que deseja continuar?</span>'))
            ->requiresConfirmation()
            ->modalDescription('')
            ->modalWidth(MaxWidth::TwoExtraLarge)

            ->action(function (array $arguments, Component $livewire): void {
                $componentPath = explode('.', $arguments['componentPath'] ?? '');
                $componentPath = array_slice($componentPath, 1);
                $livewire->data[$componentPath[0]][$componentPath[1]][$componentPath[2]] = null;
            })
            ->modalCloseButton(false)
            ->closeModalByClickingAway(false)
            ->closeModalByEscaping(false)
            ->modalSubmitActionLabel('Cancelar')
            ->modalCancelActionLabel('Continuar mesmo assim!');
    }
}
