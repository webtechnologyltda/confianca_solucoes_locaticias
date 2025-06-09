<?php

namespace App\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum RentalStatus: int implements HasColor, HasLabel
{
    case AVAILABLE = 1;
    case RENTED = 2;
    case UNAVAILABLE = 3;
    case RESERVED = 4;

    public function getLabel(): string
    {
        return match ($this) {
            self::AVAILABLE => 'Disponível',
            self::RENTED => 'Alugado',
            self::UNAVAILABLE => 'Indisponível',
            self::RESERVED => 'Reservado',
            default => 'Não Informado'
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::AVAILABLE => 'success',
            self::RENTED => 'primary',
            self::UNAVAILABLE => 'warning',
            self::RESERVED => 'info',
            default => 'gray'
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::AVAILABLE => 'fas-lock-open',
            self::RENTED => 'gmdi-attach-money-s',
            self::UNAVAILABLE => 'eos-cancel',
            self::RESERVED => 'fas-lock'
        };
    }
}
