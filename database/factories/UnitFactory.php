<?php

namespace Database\Factories;

use App\Models\Building;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Unit>
 */
class UnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'building_id' => Building::factory(),
            'reference' => fake()->text(5),
            'category' => fake()->numberBetween(1, 5),
            'status' => fake()->numberBetween(1, 4),
            'description' => fake()->text(),
            'price' => fake()->numberBetween(75000, 200000),
        ];
    }
}
