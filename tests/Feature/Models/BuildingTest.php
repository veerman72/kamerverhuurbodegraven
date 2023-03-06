<?php

use App\Models\Building;
use App\Models\Owner;
use App\Models\Unit;

test('a building can be created', function () {
    Building::factory()->create();

    expect(Building::query()->firstOrFail())
        ->toHaveKeys([
            'owner_id',
            'reference',
            'name',
            'description',
            'address',
            'zipcode',
            'city',
            'year_construction',
            'year_renovation',
            'energy_label',
            'distance_public_transport',
            'distance_center',
        ])
        ->owner_id->toBeNumeric()
        ->energy_label->toBeBool();
});

test('a building belongs to an owner', function () {
    Building::factory()->create([
        'owner_id' => Owner::factory()->create([
            'last_name' => 'Doe',
            'first_name' => 'John',
        ]),
    ]);

    expect(
        Building::query()
            ->with(['owner'])
            ->firstOrFail(),
    )
        ->owner->last_name->toBe('Doe')
        ->owner->first_name->toBe('John');
});

test('a building has two building units', function () {
    Unit::factory(2)->create([
        'building_id' => Building::factory()->create(),
    ]);

    expect(
        Building::query()
            ->with(['units'])
            ->firstOrFail(),
    )->units->toHaveCount(2);
});
