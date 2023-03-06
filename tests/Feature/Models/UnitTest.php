<?php

use App\Models\Building;
use App\Models\Owner;
use App\Models\Unit;

test('a building unit can be created', function () {
    Unit::factory()->create();

    expect(Unit::query()->firstOrFail())
        ->toHaveKeys([
            'reference',
            'building_id',
            'category',
            'status',
            'description',
            'surface',
            'price',
            'energy_costs_advanced',
            'service_charge_amount',
            'service_charge',
            'services',
            'energy_costs_included',
            'metering_electricity',
            'metering_gas',
            'metering_water',
            'independent_living_space',
            'shared_entrance',
            'furnished',
            'upholstered',
            'rooms',
            'published',
        ])
        ->building_id->toBeNumeric()
        ->category->value->toBeNumeric()
        ->status->value->toBeNumeric()
        ->price->toBeNumeric()
        ->service_charge->toBeBool()
        ->services->toBeArray()
        ->energy_costs_included->toBeBool()
        ->metering_electricity->toBeBool()
        ->metering_gas->toBeBool()
        ->metering_water->toBeBool()
        ->independent_living_space->toBeBool()
        ->shared_entrance->toBeBool()
        ->furnished->toBeBool()
        ->upholstered->toBeBool()
        ->published->toBeBool();
});

test('two building units can be created while creating a building', function () {
    $owner = Owner::factory()->create();

    Building::factory(['owner_id' => $owner])
        ->hasUnits(2)
        ->create();

    expect(Building::with(['units'])->get())
        ->toHaveCount(1)
        ->first()
        ->units->toHaveCount(2);
});

test('a building unit belongs to a building', function () {
    Unit::factory()->create([
        'building_id' => Building::factory()->create(['reference' => 'ROMEO']),
    ]);

    expect(Unit::with(['building'])->firstOrFail())->building->reference->toBe('ROMEO');
});
