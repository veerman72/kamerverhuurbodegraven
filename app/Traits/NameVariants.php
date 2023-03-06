<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

trait NameVariants
{
    public function nameWithInitials(): string
    {
        return Str::of(self::firstLetterOfEachWord($this->first_name, '.'))
            ->finish('.')
            ->upper()
            ->when(
                $this->prefix,
                fn ($string) => $string->append(
                    Str::of($this->prefix)
                        ->lower()
                        ->prepend(' '),
                ),
            )
            ->append(
                Str::of($this->last_name)
                    ->headline()
                    ->prepend(' '),
            )
            ->value();
    }

    public function authorizedName(): string
    {
        return Str::of($this->last_name)
            ->append(', ')
            ->append($this->first_name)
            ->when($this->prefix, fn ($string) => $string->append(' ')->append($this->prefix))
            ->value();
    }

    public function fullName(): string
    {
        return Str::of($this->first_name)
            ->when($this->prefix, fn ($string) => $string->append(' ')->append($this->prefix))
            ->append(' ')
            ->append($this->last_name)
            ->value();
    }

    public function abbreviation(): string
    {
        return Str::of(self::firstLetterOfEachWord($this->first_name)->upper())
            ->when($this->prefix, fn ($string) => $string->append(self::firstLetterOfEachWord($this->prefix)->lower()))
            ->append(self::firstLetterOfEachWord($this->last_name)->upper())
            ->value();
    }

    private static function firstLetterOfEachWord(string $string, string $delimiter = ''): Stringable
    {
        return Str::of(
            Str::of($string)
                ->squish()
                ->explode(' ')
                ->map(fn ($word) => $word[0])
                ->implode($delimiter),
        );
    }
}
