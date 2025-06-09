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

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->toArray();
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

    public static function getType(?string $type): ?string
    {
        if (empty($type)) {
            return null;
        } elseif ($type == self::APARTMENT->value) {
            return 'Apartamento';
        } elseif ($type == self::HOUSE->value) {
            return 'Casa';
        } elseif ($type == self::OFFICE->value) {
            return 'Escritório';
        } elseif ($type == self::LAND->value) {
            return 'Terreno';
        } elseif ($type == self::OTHER->value) {
            return 'Outro';
        }

        return null;
    }
}
