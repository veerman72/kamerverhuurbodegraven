<?php

namespace App\Models;

use App\Traits\NameVariants;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Owner extends Model
{
    use HasFactory, NameVariants;

    protected $casts = [
        'date_of_birth' => 'immutable_date',
    ];

    protected function iban(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => iban_to_human_format($value),
            set: fn ($value) => iban_to_machine_format($value),
        );
    }

    public function buildings(): HasMany
    {
        return $this->hasMany(Building::class);
    }
}
