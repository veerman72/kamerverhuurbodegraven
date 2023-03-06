<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SocialNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! isset($value) || $value === '') {
            $fail('not a valid number');

            return;
        }

        if (! self::isValidLength($value)) {
            $fail('The social number must have 9 digits.');

            return;
        }

        if (! self::isValid($value)) {
            $fail("The social number doesn't comply with the rules.");
        }
    }

    public static function isValid(mixed $value): bool
    {
        $sum = 0;

        for ($i = 9; $i > 0; $i--) {
            $sum += ($i == 1 ? -1 : $i) * $value[9 - $i];
        }

        return $sum && $sum % 11 === 0;
    }

    public static function isValidLength(mixed $value): bool
    {
        return preg_match('/^\d{9}$/', $value);
    }
}
