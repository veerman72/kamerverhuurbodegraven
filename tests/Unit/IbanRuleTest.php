<?php

use App\Rules\Iban;

test('check if iban\'s are valid', function ($iban) {
    expect(Iban::isValid($iban))->toBeTrue();
})->with(data: 'iban-numbers.valid');

test('check if iban\'s are invalid', function ($iban) {
    expect(Iban::isValid($iban))->toBeFalse();
})->with(data: 'iban-numbers.invalid');
