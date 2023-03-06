<?php

namespace App\Actions\Contracts;

use App\Enums\ContractStatus;
use App\Enums\RentalStatus;
use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Unit;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class CreateContract extends Controller
{
    public function handle(array $input): Contract
    {
        $validated = $this->validateInput(input: $input)
            ->safe()
            ->collect();

        $start = CarbonImmutable::create($validated['start']);
        $end = $start->addMonths($validated['duration'])->subDay();

        $unit = Unit::findOrFail($validated['unit']);

        $data = [
            'unit_id' => $unit->id,
            'start' => $start,
            'notice_period' => $validated['notice_period'],
            'duration' => $validated['duration'],
            'price' => $validated['price'],
            'deposit' => $validated['deposit'],
        ];

        $defaults = [
            'reference' => self::getReference(unit: $unit, start: $start),
            'document' => now(),
            'end' => $end,
            'next_expiration' => $end,
            'status' => ContractStatus::DRAFT,
        ];

        $contract = Contract::query()->create([...$data, ...$defaults]);
        $contract->tenants()->attach($validated['tenants']);
        $unit->update(['price' => $validated['price'], 'status' => RentalStatus::RESERVED]);

        return $contract;
    }

    private function validateInput(array $input): \Illuminate\Validation\Validator
    {
        return Validator::make($input, [
            'unit' => ['required', 'integer'],
            'tenants' => ['required', 'array'],
            'tenants.*' => ['required', 'integer'],
            'start' => ['required', 'date'],
            'duration' => ['required', 'integer'],
            'notice_period' => ['required', 'integer'],
            'price' => ['required', 'integer'],
            'deposit' => ['required', 'integer'],
        ]);
    }

    private static function getReference(Unit $unit, Carbon|CarbonImmutable $start): string
    {
        return Arr::join([$unit->reference, $start->format('Ymd'), ContractStatus::DRAFT->description()], '_');
    }
}
