<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum RentalStatus: int
{
    use EnumTrait;

    case AVAILABLE = 1;
    case EXPECTED = 2;
    case RENTED_OUT = 3;
    case RESERVED = 4;
    case UNAVAILABLE = 5;

    public function description(): string
    {
        return match ($this) {
            self::AVAILABLE => 'available',
            self::EXPECTED => 'expected soon',
            self::RENTED_OUT => 'rented out',
            self::RESERVED => 'rented with reservation',
            self::UNAVAILABLE => 'temporary not available',
        };
    }
}
