<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tenant::query()->create([
            'last_name' => 'Kumanaku',
            'first_name' => 'Ermixhen',
            'place_of_birth' => 'Albania',
            'date_of_birth' => '1993-09-14',
            'address' => 'Via Barletta 169',
            'zipcode' => '76125',
            'city' => 'Trani (Italie)',
            'email' => 'ermix.kuma@gmail.com',
            'phone' => '+393486924125',
            'employer' => 'Royal A-ware',
            'id_document_type' => 2,
            'id_document_number' => 'CA34250KC',
            'social_number' => 589488363,
        ]);
        Tenant::query()->create([
            'last_name' => 'Culkina',
            'first_name' => 'Marija',
            'place_of_birth' => 'Ventspils (Latvia)',
            'date_of_birth' => '2000-12-18',
            'address' => 'Brivibas iela 17',
            'zipcode' => 'LV-3601',
            'city' => 'Ventspils (Latvia)',
            'email' => 'marijach2000@gmail.com',
            'phone' => '+37122504808',
            'employer' => 'Head Staffing',
            'id_document_type' => 2,
            'id_document_number' => 'PA2095487',
            'social_number' => 475387260,
        ]);
        Tenant::query()->create([
            'last_name' => 'Zolnieruk',
            'first_name' => 'Kamil',
            'place_of_birth' => 'Torun (Poland)',
            'date_of_birth' => '1992-07-12',
            'address' => 'Nicolaas Beetsstraat 62 A',
            'zipcode' => '3117ST',
            'city' => 'Schiedam',
            'email' => 'kamilzolnieruk@gmail.com',
            'phone' => '+31620946929',
            'employer' => 'Laban Foods',
            'id_document_type' => 2,
            'id_document_number' => 'DCR385338',
            'social_number' => 394000122,
        ]);
        Tenant::query()->create([
            'last_name' => 'Jakubiec',
            'first_name' => 'Gracjana',
            'place_of_birth' => 'Wodzislaw Slaski (Poland)',
            'date_of_birth' => '1999-08-08',
            'address' => 'Nicolaas Beetsstraat 62 A',
            'zipcode' => '3117ST',
            'city' => 'Schiedam',
            'email' => 'gracjana.jakubiec1@gmail.com',
            'phone' => '+31623873097',
            'employer' => 'Laban Foods',
            'id_document_type' => 2,
            'id_document_number' => 'DAH642709',
            'social_number' => 412514722,
        ]);
        Tenant::query()->create([
            'last_name' => 'Boroch',
            'first_name' => 'Kamil Marek',
            'place_of_birth' => 'Wabrzezno (Poland)',
            'date_of_birth' => '1996-07-31',
            'address' => 'Zuiderpark 47c',
            'zipcode' => '1433PS',
            'city' => 'Kudelstaart',
            'email' => 'kamil310796@gmail.com',
            'phone' => '+31626777671',
            'employer' => 'Zijerveld Cheese Unlimited',
            'id_document_type' => 2,
            'id_document_number' => 'AZE251278',
            'social_number' => 345752016,
        ]);
        Tenant::query()->create([
            'last_name' => 'Krajewska',
            'first_name' => 'Martyna Elzbieta',
            'place_of_birth' => 'Torun (Poland)',
            'date_of_birth' => '1997-02-12',
            'address' => 'Zuiderpark 47c',
            'zipcode' => '1433PS',
            'city' => 'Kudelstaart',
            'email' => 'krajmartyna@gmail.com',
            'phone' => '+31633214175',
            'employer' => 'Wiegmans Facilitaire Diensten BV',
            'id_document_type' => 2,
            'id_document_number' => 'CBL917931',
            'social_number' => 581093793,
        ]);
    }
}
