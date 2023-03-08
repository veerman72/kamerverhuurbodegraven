<?php

use App\Models\Tenant;

test('a tenant can be created', function () {
    Tenant::factory()->create([
        'last_name' => 'Doe',
        'first_name' => 'John',
    ]);

    expect(Tenant::query()->firstOrFail())
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
            'employer',
            'id_document_type',
            'id_document_number',
            'social_number',
        ])
        ->first_name->toBe('John')
        ->last_name->toBe('Doe')
        ->id_document_type->value->toBeNumeric()
        ->id_document_number->toBeString()
        ->social_number->toBeNumeric();
});

it('can return an tenant name with initials', function () {
    Tenant::factory()->create([
        'last_name' => 'Kennedy',
        'first_name' => 'John Fitzgerald',
    ]);

    expect(Tenant::query()->firstOrFail())
        ->nameWithInitials()
        ->toBe('J.F. Kennedy');
});
