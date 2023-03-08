<?php

namespace Database\Seeders;

use App\Enums\RentalCategory;
use App\Enums\RentalStatus;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::query()->create([
            'reference' => 'KS2801',
            'building_id' => 1,
            'category' => RentalCategory::RETAIL->value,
        ]);
        Unit::query()->create([
            'reference' => 'KS2811',
            'building_id' => 1,
            'category' => RentalCategory::ROOM->value,
        ]);
        Unit::query()->create([
            'reference' => 'KS2812',
            'building_id' => 1,
            'category' => RentalCategory::ROOM->value,
        ]);
        Unit::query()->create([
            'reference' => 'KS2813',
            'building_id' => 1,
            'category' => RentalCategory::ROOM->value,
        ]);
        Unit::query()->create([
            'reference' => 'KS2814',
            'building_id' => 1,
            'category' => RentalCategory::ROOM->value,
        ]);
        Unit::query()->create([
            'reference' => 'KS28A',
            'building_id' => 1,
            'category' => RentalCategory::STUDIO->value,
        ]);
        Unit::query()
            ->create([
                'reference' => 'KS30',
                'building_id' => 2,
                'category' => RentalCategory::APARTMENT->value,
                'independent_living_space' => true,
                'furnished' => true,
                'upholstered' => true,
            ])
            ->contract_provisions()
            ->attach([1, 4, 6, 7, 8, 9, 10]);
        Unit::query()
            ->create([
                'reference' => 'KS32',
                'building_id' => 3,
                'category' => RentalCategory::APARTMENT->value,
                'independent_living_space' => true,
                'furnished' => true,
                'upholstered' => true,
            ])
            ->contract_provisions()
            ->attach([1, 4, 6, 7, 8, 9, 10]);
        Unit::query()->create([
            'reference' => 'KS4701',
            'building_id' => 4,
            'category' => RentalCategory::RETAIL->value,
        ]);
        Unit::query()->create([
            'reference' => 'KS4711',
            'building_id' => 4,
            'category' => RentalCategory::STUDIO->value,
        ]);
        Unit::query()->create([
            'reference' => 'KS4712',
            'building_id' => 4,
            'category' => RentalCategory::STUDIO->value,
        ]);
        Unit::query()->create([
            'reference' => 'KS4901',
            'building_id' => 5,
            'category' => RentalCategory::RETAIL->value,
        ]);
        Unit::query()
            ->create([
                'reference' => 'KS4911',
                'building_id' => 5,
                'category' => RentalCategory::APARTMENT->value,
                'status' => RentalStatus::RENTED_OUT,
                'price' => 115000,
                'energy_costs_advanced' => 15000,
                'energy_costs_included' => true,
                'independent_living_space' => true,
                'shared_entrance' => true,
                'furnished' => true,
                'upholstered' => true,
            ])
            ->contract_provisions()
            ->attach([2, 4, 5, 6, 7, 8, 9, 10]);
        Unit::query()
            ->create([
                'reference' => 'KS4921',
                'building_id' => 5,
                'category' => RentalCategory::APARTMENT->value,
                'status' => RentalStatus::RESERVED,
                'price' => 115000,
                'energy_costs_advanced' => 15000,
                'energy_costs_included' => true,
                'independent_living_space' => true,
                'shared_entrance' => true,
                'furnished' => true,
                'upholstered' => true,
            ])
            ->contract_provisions()
            ->attach([2, 4, 5, 6, 7, 8, 9, 10]);
        //        Unit::query()->create([
        //            'reference' => 'KS4922',
        //            'building_id' => 5,
        //            'category' => RentalCategory::ROOM->value,
        //        ]);
        Unit::query()->create([
            'reference' => 'MS0201',
            'building_id' => 6,
            'category' => RentalCategory::ROOM->value,
        ]);
        Unit::query()->create([
            'reference' => 'MS0202',
            'building_id' => 6,
            'category' => RentalCategory::ROOM->value,
        ]);
        Unit::query()->create([
            'reference' => 'MS0211',
            'building_id' => 6,
            'category' => RentalCategory::ROOM->value,
        ]);
        Unit::query()->create([
            'reference' => 'MS0212',
            'building_id' => 6,
            'category' => RentalCategory::ROOM->value,
        ]);
        Unit::query()->create([
            'reference' => 'MS0213',
            'building_id' => 6,
            'category' => RentalCategory::ROOM->value,
        ]);
        Unit::query()->create([
            'reference' => 'MS0214',
            'building_id' => 6,
            'category' => RentalCategory::ROOM->value,
        ]);
        Unit::query()->create([
            'reference' => 'MS0215',
            'building_id' => 6,
            'category' => RentalCategory::APARTMENT->value,
        ]);
        Unit::query()->create([
            'reference' => 'MS0216',
            'building_id' => 6,
            'category' => RentalCategory::ROOM->value,
        ]);
        Unit::query()->create([
            'reference' => 'MS04',
            'building_id' => 7,
            'category' => RentalCategory::STUDIO->value,
        ]);
        Unit::query()->create([
            'reference' => 'PH33',
            'building_id' => 8,
            'category' => RentalCategory::STUDIO->value,
        ]);
        Unit::query()->create([
            'reference' => 'WB04',
            'building_id' => 14,
            'category' => RentalCategory::APARTMENT->value,
        ]);
        Unit::query()->create([
            'reference' => 'WB0401',
            'building_id' => 14,
            'category' => RentalCategory::STORAGE->value,
        ]);
        Unit::query()->create([
            'reference' => 'WB0402',
            'building_id' => 14,
            'category' => RentalCategory::STORAGE->value,
        ]);
        Unit::query()->create([
            'reference' => 'KS14',
            'building_id' => 9,
            'category' => RentalCategory::RETAIL->value,
        ]);
        Unit::query()
            ->create([
                'reference' => 'KS14b',
                'building_id' => 9,
                'category' => RentalCategory::APARTMENT->value,
                'price' => 95000,
                'energy_costs_advanced' => 10000,
                'independent_living_space' => true,
                'metering_electricity' => true,
                'metering_gas' => true,
                'metering_water' => true,
                'service_charge' => true,
                'services' => [
                    'onderhoud CV-installatie',
                    'onderhoud buitenzijde gebouw',
                    'beheer- en administratiekosten',
                ],
            ])
            ->contract_provisions()
            ->attach([1, 2, 4, 5, 6, 7, 8, 9, 10, 11]);
        Unit::query()
            ->create([
                'reference' => 'KS14c',
                'building_id' => 9,
                'category' => RentalCategory::APARTMENT->value,
                'price' => 95000,
                'energy_costs_advanced' => 10000,
                'independent_living_space' => true,
                'metering_electricity' => true,
                'metering_gas' => true,
                'metering_water' => true,
                'service_charge' => true,
                'services' => [
                    'onderhoud CV-installatie',
                    'onderhoud buitenzijde gebouw',
                    'beheer- en administratiekosten',
                ],
            ])
            ->contract_provisions()
            ->attach([1, 2, 4, 5, 6, 7, 8, 9, 10]);
        Unit::query()
            ->create([
                'reference' => 'KS14d',
                'building_id' => 9,
                'category' => RentalCategory::APARTMENT->value,
                'price' => 80000,
                'energy_costs_advanced' => 12500,
                'independent_living_space' => true,
                'metering_electricity' => true,
                'metering_gas' => true,
                'metering_water' => true,
                'service_charge' => true,
                'services' => [
                    'onderhoud CV-installatie',
                    'onderhoud buitenzijde gebouw',
                    'beheer- en administratiekosten',
                ],
            ])
            ->contract_provisions()
            ->attach([1, 2, 4, 5, 6, 7, 8, 9, 10, 11]);
        Unit::query()
            ->create([
                'reference' => 'KS14e',
                'building_id' => 9,
                'category' => RentalCategory::APARTMENT->value,
                'price' => 102000,
                'energy_costs_advanced' => 17500,
                'independent_living_space' => true,
                'metering_electricity' => true,
                'metering_gas' => true,
                'metering_water' => true,
                'service_charge' => true,
                'services' => [
                    'onderhoud CV-installatie',
                    'onderhoud buitenzijde gebouw',
                    'beheer- en administratiekosten',
                ],
            ])
            ->contract_provisions()
            ->attach([1, 2, 4, 5, 6, 7, 8, 9, 10]);
        Unit::query()
            ->create([
                'reference' => 'KS14f',
                'building_id' => 9,
                'category' => RentalCategory::STUDIO->value,
                'price' => 67500,
                'energy_costs_advanced' => 12500,
                'independent_living_space' => true,
                'metering_electricity' => true,
                'metering_gas' => true,
                'metering_water' => true,
                'service_charge' => true,
                'services' => [
                    'onderhoud CV-installatie',
                    'onderhoud buitenzijde gebouw',
                    'beheer- en administratiekosten',
                ],
            ])
            ->contract_provisions()
            ->attach([1, 2, 4, 5, 6, 7, 8, 9, 10]);
        Unit::query()->create([
            'reference' => 'KS40',
            'building_id' => 10,
            'category' => RentalCategory::APARTMENT->value,
            'independent_living_space' => true,
            'metering_electricity' => true,
            'metering_gas' => true,
            'metering_water' => true,
        ]);
        Unit::query()
            ->create([
                'reference' => 'KS40a',
                'building_id' => 10,
                'category' => RentalCategory::APARTMENT->value,
                'price' => 99500,
                'energy_costs_advanced' => 14000,
                'independent_living_space' => true,
                'metering_electricity' => true,
                'metering_gas' => true,
                'metering_water' => true,
            ])
            ->contract_provisions()
            ->attach([1, 2, 4, 5, 6, 7, 8, 9, 10]);
        Unit::query()
            ->create([
                'reference' => 'KS42',
                'building_id' => 11,
                'category' => RentalCategory::STUDIO->value,
                'price' => 75000,
                'energy_costs_advanced' => 12500,
                'independent_living_space' => true,
                'metering_electricity' => true,
                'metering_gas' => true,
                'metering_water' => true,
            ])
            ->contract_provisions()
            ->attach([1, 2, 4, 5, 6, 7, 8, 9, 10]);
        Unit::query()
            ->create([
                'reference' => 'KS42a',
                'building_id' => 11,
                'category' => RentalCategory::APARTMENT->value,
                'price' => 92500,
                'energy_costs_advanced' => 17500,
                'independent_living_space' => true,
                'metering_electricity' => true,
                'metering_gas' => true,
                'metering_water' => true,
            ])
            ->contract_provisions()
            ->attach([1, 2, 4, 5, 6, 7, 8, 9, 10]);
        Unit::query()
            ->create([
                'reference' => 'KS42b',
                'building_id' => 11,
                'category' => RentalCategory::APARTMENT->value,
                'price' => 105000,
                'energy_costs_advanced' => 8500,
                'independent_living_space' => true,
                'metering_electricity' => true,
                'metering_gas' => true,
                'metering_water' => true,
            ])
            ->contract_provisions()
            ->attach([1, 2, 4, 5, 6, 7, 8, 9, 10]);
        Unit::query()
            ->create([
                'reference' => 'KS42c',
                'building_id' => 11,
                'category' => RentalCategory::APARTMENT->value,
                'price' => 95000,
                'energy_costs_advanced' => 12500,
                'independent_living_space' => true,
                'metering_electricity' => true,
                'metering_gas' => true,
                'metering_water' => true,
                'service_charge' => true,
                'services' => [
                    'onderhoud CV-installatie',
                    'onderhoud buitenzijde gebouw',
                    'beheer- en administratiekosten',
                ],
            ])
            ->contract_provisions()
            ->attach([1, 2, 4, 5, 6, 7, 8, 9, 10]);
        Unit::query()
            ->create([
                'reference' => 'RP24',
                'building_id' => 12,
                'category' => RentalCategory::STUDIO->value,
                'price' => 55000,
                'energy_costs_advanced' => 12500,
                'independent_living_space' => true,
                'metering_electricity' => true,
                'metering_gas' => true,
                'metering_water' => true,
            ])
            ->contract_provisions()
            ->attach([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        Unit::query()
            ->create([
                'reference' => 'RP26',
                'building_id' => 13,
                'category' => RentalCategory::STUDIO->value,
                'price' => 80000,
                'energy_costs_advanced' => 8500,
                'independent_living_space' => true,
                'metering_electricity' => true,
                'metering_gas' => true,
                'metering_water' => true,
            ])
            ->contract_provisions()
            ->attach([1, 2, 4, 5, 6, 7, 8, 9, 10]);
    }
}
