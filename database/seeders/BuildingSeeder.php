<?php

namespace Database\Seeders;

use App\Models\Building;
use Illuminate\Database\Seeder;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Building::query()->create([
            'owner_id' => 1,
            'name' => 'Kerkstraat 28',
            'address' => 'Kerkstraat 28',
            'zipcode' => '2411AE',
            'city' => 'Bodegraven',
            'reference' => 'KS28',
            'year_construction' => null,
            'year_renovation' => null,
            'distance_public_transport' => 500,
            'distance_center' => null,
        ]);
        Building::query()->create([
            'owner_id' => 1,
            'name' => 'Kerkstraat 30',
            'address' => 'Kerkstraat 30',
            'zipcode' => '2411AE',
            'city' => 'Bodegraven',
            'reference' => 'KS30',
            'year_construction' => null,
            'year_renovation' => null,
            'distance_public_transport' => 500,
            'distance_center' => null,
        ]);
        Building::query()->create([
            'owner_id' => 1,
            'name' => 'Kerkstraat 32',
            'address' => 'Kerkstraat 32',
            'zipcode' => '2411AE',
            'city' => 'Bodegraven',
            'reference' => 'KS32',
            'year_construction' => null,
            'year_renovation' => null,
            'distance_public_transport' => 500,
            'distance_center' => null,
        ]);
        Building::query()->create([
            'owner_id' => 1,
            'name' => 'Kerkstraat 47',
            'address' => 'Kerkstraat 47',
            'zipcode' => '2411AA',
            'city' => 'Bodegraven',
            'reference' => 'KS47',
            'year_construction' => null,
            'year_renovation' => null,
            'distance_public_transport' => 500,
            'distance_center' => null,
        ]);
        Building::query()->create([
            'owner_id' => 1,
            'name' => 'Kerkstraat 49',
            'address' => 'Kerkstraat 49',
            'zipcode' => '2411AA',
            'city' => 'Bodegraven',
            'reference' => 'KS49',
            'year_construction' => null,
            'year_renovation' => 2023,
            'distance_public_transport' => 500,
            'distance_center' => null,
        ]);
        Building::query()->create([
            'owner_id' => 1,
            'name' => 'Mauritsstraat 2',
            'address' => 'Mauritsstraat 2',
            'zipcode' => '2411CN',
            'city' => 'Bodegraven',
            'reference' => 'MS02',
            'year_construction' => null,
            'year_renovation' => null,
            'distance_public_transport' => 200,
            'distance_center' => 200,
        ]);
        Building::query()->create([
            'owner_id' => 1,
            'name' => 'Mauritsstraat 4',
            'address' => 'Mauritsstraat 4',
            'zipcode' => '2411CN',
            'city' => 'Bodegraven',
            'reference' => 'MS04',
            'year_construction' => null,
            'year_renovation' => null,
            'distance_public_transport' => 200,
            'distance_center' => 200,
        ]);
        Building::query()->create([
            'owner_id' => 1,
            'name' => 'Prins Hendrikstraat 33',
            'address' => 'Prins Hendrikstraat 33',
            'zipcode' => '2411CS',
            'city' => 'Bodegraven',
            'reference' => 'PH33',
            'year_construction' => null,
            'year_renovation' => null,
            'distance_public_transport' => 200,
            'distance_center' => 200,
        ]);

        Building::query()->create([
            'owner_id' => 2,
            'name' => 'Kerkstraat 14',
            'address' => 'Kerkstraat 14',
            'zipcode' => '2411AD',
            'city' => 'Bodegraven',
            'reference' => 'KS14',
            'year_construction' => null,
            'year_renovation' => null,
            'distance_public_transport' => 500,
            'distance_center' => null,
        ]);
        Building::query()->create([
            'owner_id' => 2,
            'name' => 'Kerkstraat 40',
            'address' => 'Kerkstraat 40',
            'zipcode' => '2411AE',
            'city' => 'Bodegraven',
            'reference' => 'KS40',
            'year_construction' => null,
            'year_renovation' => null,
            'distance_public_transport' => 500,
            'distance_center' => null,
        ]);
        Building::query()->create([
            'owner_id' => 2,
            'name' => 'Kerkstraat 42',
            'address' => 'Kerkstraat 42',
            'zipcode' => '2411AE',
            'city' => 'Bodegraven',
            'reference' => 'KS42',
            'year_construction' => null,
            'year_renovation' => null,
            'distance_public_transport' => 500,
            'distance_center' => null,
        ]);
        Building::query()->create([
            'owner_id' => 2,
            'name' => 'Rijnpoort 24',
            'address' => 'Rijnpoort 24',
            'zipcode' => '2411SC',
            'city' => 'Bodegraven',
            'reference' => 'RP24',
            'year_construction' => null,
            'year_renovation' => null,
            'distance_public_transport' => 500,
            'distance_center' => null,
        ]);
        Building::query()->create([
            'owner_id' => 2,
            'name' => 'Rijnpoort 26',
            'address' => 'Rijnpoort 26',
            'zipcode' => '2411SC',
            'city' => 'Bodegraven',
            'reference' => 'RP26',
            'year_construction' => null,
            'year_renovation' => null,
            'distance_public_transport' => 500,
            'distance_center' => null,
        ]);
        Building::query()->create([
            'owner_id' => 1,
            'name' => 'Weeshuisbaan 4',
            'address' => 'Weeshuisbaan 4',
            'zipcode' => '2411AA',
            'city' => 'Bodegraven',
            'reference' => 'WB04',
            'year_construction' => null,
            'year_renovation' => null,
            'distance_public_transport' => 500,
            'distance_center' => null,
        ]);
    }
}
