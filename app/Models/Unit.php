<?php

namespace App\Models;

use App\Enums\RentalCategory;
use App\Enums\RentalStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Unit extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'category' => RentalCategory::class,
        'status' => RentalStatus::class,
        'service_charge' => 'boolean',
        'services' => 'array',
        'energy_costs_included' => 'boolean',
        'metering_electricity' => 'boolean',
        'metering_gas' => 'boolean',
        'metering_water' => 'boolean',
        'independent_living_space' => 'boolean',
        'shared_entrance' => 'boolean',
        'furnished' => 'boolean',
        'upholstered' => 'boolean',
        'published' => 'boolean',
    ];

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function contract_provisions(): BelongsToMany
    {
        return $this->belongsToMany(ContractExceptionalProvision::class);
    }
}
