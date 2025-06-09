<?php

namespace App\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum AnalysisStatus: int implements HasColor, HasLabel
{
    case PENDING = 1;
    case APPROVED = 2;
    case REJECTED = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'Em análise',
            self::APPROVED => 'Aprovado',
            self::REJECTED => 'Reprovado',
            default => 'Não Informado'
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
            default => 'gray'
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::PENDING => 'heroicon-s-clock',
            self::APPROVED => 'microns-pass',
            self::REJECTED => 'eos-cancel',
        };
    }
}
