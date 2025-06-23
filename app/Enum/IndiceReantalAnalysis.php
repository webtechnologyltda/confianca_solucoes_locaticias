<?php

namespace App\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum IndiceReantalAnalysis: int implements HasColor, HasLabel
{
    case IC = 1;
    case CTZ = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::IC => 'IC - Intervalo de confiança',
            self::CTZ => 'CTZ - Certeza de confiança',
            default => 'Não informado',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::IC => 'info',
            self::CTZ => 'success',
            default => 'gray'
        };
    }

}
