<?php

namespace Database\Factories;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contract>
 */
class ContractFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reference' => fake()->text(5),
            'unit_id' => Unit::factory(),
            'document' => now(),
            'start' => now()
                ->endOfMonth()
                ->addDay(),
            'end' => now()
                ->addYear()
                ->endOfMonth(),
            'next_expiration' => now()
                ->addYear()
                ->endOfMonth()
                ->addDay(),
            'notice_period' => 1,
            'duration' => 12,
            'status' => fake()->numberBetween(1, 4),
            'price' => fake()->numberBetween(75000, 200000),
            'deposit' => fake()->numberBetween(150000, 400000),
        ];
    }
}
