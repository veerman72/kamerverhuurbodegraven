<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum RentalCategory: int
{
    use EnumTrait;

    case UNKNOWN = 0;
    case ROOM = 1;
    case STUDIO = 2;
    case APARTMENT = 3;
    case RETAIL = 4;
    case STORAGE = 5;

    public function description(): string
    {
        return match ($this) {
            self::UNKNOWN => 'unknown',
            self::ROOM => 'kamer',
            self::STUDIO => 'studio',
            self::APARTMENT => 'appartement',
            self::RETAIL => 'winkel',
            self::STORAGE => 'garage',
        };
    }
}
