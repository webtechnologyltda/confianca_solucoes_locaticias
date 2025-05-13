<?php

namespace App\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum RentalStatus: int implements HasLabel, HasColor
{
    case AVAILABLE = 1;
    case RENTED = 2;
    case UNAVAILABLE = 3;
    case RESERVED =4;

    public function getLabel(): string
    {
        return match($this) {
            self::AVAILABLE => 'Disponível',
            self::RENTED => 'Alugado',
            self::UNAVAILABLE => 'Indisponível',
            self::RESERVED => 'Reservado',
            default => 'Não Informado'
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::AVAILABLE => 'success',
            self::RENTED => 'primary',
            self::UNAVAILABLE => 'warning',
            self::RESERVED => 'info',
            default => 'gray'
        };
    }
}
