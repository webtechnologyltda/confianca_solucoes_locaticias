<?php

namespace App\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PropertyType: int implements HasColor, HasLabel
{
    case APARTMENT = 1;
    case HOUSE = 2;
    case OFFICE = 3;
    case LAND = 4;
    case OTHER = 5;

    public function getLabel(): string
    {
        return match ($this) {
            self::APARTMENT => 'Apartamento',
            self::HOUSE => 'Casa',
            self::OFFICE => 'Escritório',
            self::LAND => 'Terreno',
            self::OTHER => 'Outro',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::APARTMENT => 'success',
            self::HOUSE => 'primary',
            self::OFFICE => 'warning',
            self::LAND => 'info',
            self::OTHER => 'gray',
            default => 'danger'
        };
    }
}
