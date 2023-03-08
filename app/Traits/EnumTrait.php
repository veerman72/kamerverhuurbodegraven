<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait EnumTrait
{
    public function get(): Collection
    {
        return $this->getItem();
    }

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
            ->map(fn ($item) => $item->getItem())
            ->sortBy('description')
            ->values();
    }

    private function getItem(): Collection
    {
        return collect([
            'id' => $this->value,
            'name' => $this->name,
            'description' => $this->description(),
        ]);
    }
}
