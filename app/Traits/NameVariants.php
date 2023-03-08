<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

trait NameVariants
{
    public function scopeOrderByNameAndBirthday(Builder $query): Builder
    {
        return $query
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->orderBy('date_of_birth');
    }

    public function nameWithInitials(): string
    {
        return Str::of(self::firstLetterOfEachWord(string: $this->first_name, delimiter: '.'))
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
        return Str::of(self::firstLetterOfEachWord(string: $this->first_name)->upper())
            ->when(
                $this->prefix,
                fn ($string) => $string->append(self::firstLetterOfEachWord(string: $this->prefix)->lower()),
            )
            ->append(self::firstLetterOfEachWord(string: $this->last_name)->upper())
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
