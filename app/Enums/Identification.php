<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum Identification: int
{
    use EnumTrait;

    case PASSPORT = 1;
    case ID_CARD = 2;
    case DRIVERS_LICENCE = 3;
    case CHAMBER_OF_COMMERCE = 4;
    case OTHER = 5;

    public function description(): string
    {
        return match ($this) {
            self::PASSPORT => 'passport',
            self::ID_CARD => 'identity card',
            self::DRIVERS_LICENCE => 'drivers licence',
            self::CHAMBER_OF_COMMERCE => 'excerpt Chamber of Commerce',
            self::OTHER => 'others',
        };
    }
}
