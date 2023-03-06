<?php

namespace App\Models;

use App\Enums\ContractStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Contract extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'start' => 'immutable_date',
        'end' => 'immutable_date',
        'next_expiration' => 'date',
        'status' => ContractStatus::class,
        'service_charge' => 'boolean',
        'services' => 'array',
        'energy_costs_included' => 'boolean',
        'metering_electricity' => 'boolean',
        'metering_gas' => 'boolean',
        'metering_water' => 'boolean',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class);
    }
}
