<?php

namespace App\Models;

use App\Enums\Identification;
use App\Traits\NameVariants;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Tenant extends Model
{
    use HasFactory, NameVariants;

    protected $casts = [
        'id_document_type' => Identification::class,
        'date_of_birth' => 'immutable_date',
    ];

    protected function fullName(): Attribute
    {
        return Attribute::get(fn ($value) => Str::of($this->nameWithInitials())->value());
    }

    public function contracts(): BelongsToMany
    {
        return $this->belongsToMany(Contract::class);
    }
}
