<?php

namespace App\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum AnalysisStatus: int implements HasLabel, HasColor
{
    case PENDING = 1;
    case APPROVED = 2;
    case REJECTED = 3;
    case IN_REVIEW = 4;


    public function getLabel(): string
    {
        return match($this) {
            self::PENDING => 'Pedente',
            self::APPROVED => 'Aprovado',
            self::REJECTED => 'Rejeitado',
            self::IN_REVIEW => 'Em revisão',
            default => 'Não Informado'
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::PENDING => 'warning',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
            self::IN_REVIEW => 'info',
            default => 'gray'
        };
    }
}
