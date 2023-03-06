<?php

use App\Rules\SocialNumber;

test('check social numbers are valid', function ($number) {
    expect(SocialNumber::isValid($number))->toBeTrue();
})->with(data: 'social-numbers.valid');

test('check social numbers are invalid', function ($number) {
    expect(SocialNumber::isValid($number))->toBeFalse();
})->with(data: 'social-numbers.invalid');

test('check social number has valid length (nine digits)', function () {
    expect(SocialNumber::isValidLength('12345678'))
        ->toBeFalse()
        ->and(SocialNumber::isValidLength('1234567890'))
        ->toBeFalse()
        ->and(SocialNumber::isValidLength('1'))
        ->toBeFalse()
        ->and(SocialNumber::isValidLength(''))
        ->toBeFalse()
        ->and(SocialNumber::isValidLength('123456789'))
        ->toBeTrue();
});
