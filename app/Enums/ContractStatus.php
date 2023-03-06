<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum ContractStatus: int
{
    use EnumTrait;

    case DRAFT = 1;
    case APPROVAL = 2;
    case FINAL = 3;
    case ACTIVE = 4;
    case TERMINATED = 6;

    public function description(): string
    {
        return match ($this) {
            self::DRAFT => 'concept',
            self::APPROVAL => 'in afwachting goedkeuring',
            self::FINAL => 'definitief',
            self::ACTIVE => 'actief',
            self::TERMINATED => 'beëindigd',
        };
    }
}
