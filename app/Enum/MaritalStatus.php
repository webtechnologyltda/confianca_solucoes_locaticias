<?php

namespace App\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum MaritalStatus: int implements HasLabel, HasColor
{
    case SINGLE = 1;
    case MARRIED = 2;
    case DIVORCED = 3;
    case WIDOWED = 4;

    case STABLE_UNION = 5;

    public function getLabel(): string
    {
        return match($this) {
            self::SINGLE => 'Solteiro(a)',
            self::MARRIED => 'Casado(a)',
            self::DIVORCED => 'Divorciado(a)',
            self::WIDOWED => 'Viúvo(a)',
            self::STABLE_UNION => 'União Estável',
            default => 'Não informado',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::SINGLE => 'warning',
            self::MARRIED => 'success',
            self::DIVORCED => 'danger',
            self::WIDOWED => 'info',
            default => 'gray'
        };
    }

    public static function getSelectOptions(): array
    {
        return collect(self::cases())->mapWithKeys(function ($status) {
            return [$status->value => $status->getLabel()];
        })->toArray();
    }
}
