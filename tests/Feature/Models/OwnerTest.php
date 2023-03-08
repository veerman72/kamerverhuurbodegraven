<?php

use App\Models\Owner;

it('can create an owner', function () {
    Owner::factory()->create([
        'last_name' => 'Doe',
        'first_name' => 'John',
    ]);

    expect(Owner::query()->firstOrFail())
        ->toHaveKeys([
            'last_name',
            'prefix',
            'first_name',
            'place_of_birth',
            'date_of_birth',
            'address',
            'zipcode',
            'city',
            'email',
            'phone',
            'iban',
            'iban_owner',
        ])
        ->first_name->toBe('John')
        ->last_name->toBe('Doe');
});

it('can return an owner name with initials', function () {
    Owner::factory()->create([
        'last_name' => 'Kennedy',
        'first_name' => 'John Fitzgerald',
    ]);

    expect(Owner::query()->firstOrFail())
        ->nameWithInitials()
        ->toBe('J.F. Kennedy');
});

test('an owner has a human readable IBAN-number', function () {
    Owner::factory()->create([
        'iban' => 'NL14RABO4040435087',
    ]);

    expect(Owner::query()->firstOrFail())->iban->toBe('NL14 RABO 4040 4350 87');
});
