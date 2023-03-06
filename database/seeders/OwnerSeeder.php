<?php

namespace Database\Seeders;

use App\Models\Owner;
use Illuminate\Database\Seeder;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Owner::query()->create([
            'last_name' => 'Veerman',
            'first_name' => 'Johan Pieter',
            'place_of_birth' => 'Bodegraven',
            'date_of_birth' => '1973-10-24',
            'address' => 'Postbus 13',
            'zipcode' => '2410AA',
            'city' => 'Bodegraven',
            'email' => 'pieter@kamerverhuurbodegraven.nl',
            'phone' => '+31 6 53 780 455',
            'iban' => 'NL83 ABNA 0534 1541 07',
            'iban_owner' => 'J.P. Veerman',
        ]);
        Owner::query()->create([
            'last_name' => 'Veerman',
            'first_name' => 'Johan Pieter',
            'place_of_birth' => 'Bodegraven',
            'date_of_birth' => '1942-10-18',
            'address' => 'Postbus 13',
            'zipcode' => '2410AA',
            'city' => 'Bodegraven',
            'email' => 'riek.veerman@gmail.com',
            'phone' => '+31 6 20 96 38 04',
            'iban' => 'NL38 ABNA 0455 7575 77',
            'iban_owner' => 'J.P. Veerman',
        ]);
    }
}
