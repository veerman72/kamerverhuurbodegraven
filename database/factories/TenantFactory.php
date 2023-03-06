<?php

namespace Database\Factories;

use App\Enums\Identification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant>
 */
class TenantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'last_name' => fake()->lastName(),
            'first_name' => fake()->firstName(),
            'place_of_birth' => fake()->city(),
            'date_of_birth' => fake()->date(),
            'address' => fake()->streetAddress(),
            'zipcode' => fake()->postcode(),
            'city' => fake()->city(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'employer' => fake()->company(),
            'id_document_type' => fake()->numberBetween(Identification::PASSPORT->value, Identification::OTHER->value),
            'id_document_number' => fake()->isbn13(),
            'social_number' => fake()->idNumber(),
        ];
    }
}
