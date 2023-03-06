<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait EnumTrait
{
    public static function all(): Collection
    {
        return self::getCollection();
    }

    public static function toArray(): array
    {
        return self::getCollection()->toArray();
    }

    private static function getCollection(): Collection
    {
        return collect(self::cases())
            ->map(
                fn ($item) => [
                    'id' => $item->value,
                    'name' => $item->name,
                    'description' => $item->description(),
                ],
            )
            ->sortBy('description')
            ->values();
    }
}
