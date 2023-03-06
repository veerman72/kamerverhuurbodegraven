<?php

use App\Traits\NameVariants;

it('can return a persons full name', function ($last, $first, $prefix, $result) {
    $person = new class
    {
        use NameVariants;
    };

    $person->last_name = $last;
    $person->first_name = $first;
    $person->prefix = $prefix;

    expect($person->fullName())->toBe($result);
})->with([
    ['Kennedy', 'John Fitzgerald', null, 'John Fitzgerald Kennedy'],
    ['Herten', 'Freek', 'van der', 'Freek van der Herten'],
]);

it('can return a persons authorized name', function ($last, $first, $prefix, $result) {
    $person = new class
    {
        use NameVariants;
    };

    $person->last_name = $last;
    $person->first_name = $first;
    $person->prefix = $prefix;

    expect($person->authorizedName())->toBe($result);
})->with([
    ['Kennedy', 'John Fitzgerald', null, 'Kennedy, John Fitzgerald'],
    ['Herten', 'Freek', 'van der', 'Herten, Freek van der'],
]);

it('can return a persons name with initials', function ($last, $first, $prefix, $result) {
    $person = new class
    {
        use NameVariants;
    };

    $person->last_name = $last;
    $person->first_name = $first;
    $person->prefix = $prefix;

    expect($person->nameWithInitials())->toBe($result);
})->with([['Kennedy', 'John Fitzgerald', null, 'J.F. Kennedy'], ['Herten', 'Freek', 'van der', 'F. van der Herten']]);

it('can return a persons abbreviation', function ($last, $first, $prefix, $result) {
    $person = new class
    {
        use NameVariants;
    };

    $person->last_name = $last;
    $person->first_name = $first;
    $person->prefix = $prefix;

    expect($person->abbreviation())->toBe($result);
})->with([['Kennedy', 'John Fitzgerald', null, 'JFK'], ['Herten', 'Freek', 'van der', 'FvdH']]);
