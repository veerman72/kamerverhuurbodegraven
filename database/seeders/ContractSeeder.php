<?php

namespace Database\Seeders;

use App\Enums\ContractStatus;
use App\Models\Contract;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class ContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Contract::query()
            ->create([
                'reference' => 'KS30_20230123',
                'unit_id' => Unit::query()
                    ->where('reference', '=', 'KS30')
                    ->get('id')
                    ->first()->id,
                'document' => '2023-01-23 10:04:18',
                'start' => '2023-02-01',
                'end' => '2024-01-31',
                'next_expiration' => '2024-01-31',
                'notice_period' => 1,
                'duration' => 12,
                'status' => ContractStatus::ACTIVE,
                'price' => 125000,
                'deposit' => 250000,
            ])
            ->tenants()
            ->attach([1, 2]);
        Contract::query()
            ->create([
                'reference' => 'KS4911_20230308',
                'unit_id' => Unit::query()
                    ->where('reference', '=', 'KS4911')
                    ->get('id')
                    ->first()->id,
                'document' => '2023-03-06 13:38:18',
                'start' => '2023-04-01',
                'end' => '2024-03-31',
                'next_expiration' => '2024-03-31',
                'notice_period' => 1,
                'duration' => 12,
                'status' => ContractStatus::FINAL,
                'price' => 115000,
                'deposit' => 230000,
                'energy_costs_advanced' => 15000,
                'energy_costs_included' => true,
            ])
            ->tenants()
            ->attach([3, 4]);
        Contract::query()
            ->create([
                'reference' => 'KS4921_20230306_concept',
                'unit_id' => Unit::query()
                    ->where('reference', '=', 'KS4921')
                    ->get('id')
                    ->first()->id,
                'document' => '2023-03-06 13:46:54',
                'start' => '2023-04-01',
                'end' => '2024-03-31',
                'next_expiration' => '2024-03-31',
                'notice_period' => 1,
                'duration' => 12,
                'status' => ContractStatus::APPROVAL,
                'price' => 115000,
                'deposit' => 230000,
                'energy_costs_advanced' => 15000,
                'energy_costs_included' => true,
            ])
            ->tenants()
            ->attach([5, 6]);
    }
}
