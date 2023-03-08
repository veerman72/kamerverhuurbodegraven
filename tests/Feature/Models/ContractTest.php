<?php

use App\Actions\Contracts\CreateContract;
use App\Actions\Contracts\GenerateContractRoz2017;
use App\Enums\ContractStatus;
use App\Enums\Identification;
use App\Enums\RentalStatus;
use App\Models\Building;
use App\Models\Contract;
use App\Models\Owner;
use App\Models\Tenant;
use App\Models\Unit;
use App\Models\User;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Arr;
use function Pest\Laravel\actingAs;

test('a contract can be created via factory', function () {
    Contract::factory()->create();

    expect(Contract::query()->firstOrFail())
        ->toHaveKeys([
            'reference',
            'unit_id',
            'document',
            'start',
            'end',
            'next_expiration',
            'notice_period',
            'duration',
            'status',
            'price',
            'deposit',
            'energy_costs_advanced',
            'service_charge_amount',
            'service_charge',
            'services',
            'energy_costs_included',
            'metering_electricity',
            'metering_gas',
            'metering_water',
        ])
        ->unit_id->toBeNumeric()
        ->notice_period->toBeNumeric()
        ->duration->toBeNumeric()
        ->status->value->toBeNumeric()
        ->price->toBeNumeric()
        ->deposit->toBeNumeric()
        ->service_charge->toBeBool()
        ->services->toBeArray()
        ->energy_costs_included->toBeBool()
        ->metering_electricity->toBeBool()
        ->metering_gas->toBeBool()
        ->metering_water->toBeBool();
});

test('a contract can be created via class', function () {
    $contract = new CreateContract();
    $data = [
        'unit' => Unit::factory(['reference' => 'DALLAS'])->create()->id,
        'tenants' => [Tenant::factory()->create(['last_name' => 'Kennedy'])->id],
        'start' => today()->toDateString(),
        'duration' => 12,
        'notice_period' => 3,
        'price' => 900,
        'deposit' => 1964,
    ];

    expect($contract->handle($data))
        ->toBeInstanceOf(Contract::class)
        ->start->toDateString()
        ->toBe(today()->toDateString())
        ->end->toDateString()
        ->toBe(
            today()
                ->addMonths(12)
                ->subDay()
                ->toDateString(),
        )
        ->reference->toBe('DALLAS_'.today()->format('Ymd').'_concept')
        ->price->toBe(900)
        ->deposit->toBe(1964)
        ->status->toBe(ContractStatus::DRAFT)
        ->unit->first()
        ->status->toBe(RentalStatus::RESERVED)
        ->tenants->first()
        ->last_name->toBe('Kennedy');
});

test('a contract can be created via POST request ', function () {
    $unit = Unit::factory([
        'building_id' => Building::factory(['owner_id' => Owner::factory()->create()])->create(),
        'reference' => 'BRKLN',
        'price' => 95000,
    ])->create();

    $tenant_1 = Tenant::factory()->create([
        'last_name' => 'Doe',
        'first_name' => 'John',
        'id_document_type' => Identification::DRIVERS_LICENCE,
        'id_document_number' => 'RBW0123456',
    ]);

    $tenant_2 = Tenant::factory()->create([
        'last_name' => 'Doe',
        'first_name' => 'Jane',
        'id_document_type' => Identification::ID_CARD,
        'id_document_number' => 'ID0123456',
    ]);

    $duration = 12;
    $rental_price = 97500;
    $start = today()
        ->startOfMonth()
        ->addMonth()
        ->toImmutable();
    $end = today()
        ->addMonths($duration)
        ->endOfMonth()
        ->toImmutable();

    $form = [
        'unit' => $unit->id,
        'tenants' => [$tenant_1->id, $tenant_2->id],
        'start' => $start->toDateString(),
        'duration' => $duration,
        'notice_period' => 3,
        'price' => $rental_price,
        'deposit' => $rental_price * 2,
    ];

    actingAs(User::factory()->make())->post(route('contracts.store'), $form);

    $contracts = Contract::with(['unit.building.owner', 'tenants'])->get();

    expect($contracts)
        ->toHaveCount(1)
        ->and($contracts->first())
        ->toBeInstanceOf(Contract::class)
        ->start->toDateString()
        ->toBe($start->toDateString())
        ->end->toDateString()
        ->toBe($end->toDateString())
        ->next_expiration->toDateString()
        ->toBe($end->toDateString())
        ->price->toBe($rental_price)
        ->reference->toBe(
            Arr::join([$unit->reference, $start->format('Ymd'), ContractStatus::DRAFT->description()], '_'),
        )
        ->and($contracts->first()->unit->first())
        ->toBeInstanceOf(Unit::class)
        ->reference->toBe($unit->reference)
        ->price->toBe($rental_price)
        ->status->toBe(RentalStatus::RESERVED)
        ->and($contracts->first()->tenants)
        ->toHaveLength(2)
        ->and($contracts->first()->tenants->first())
        ->toBeInstanceOf(Tenant::class)
        ->first_name->toBe('John')
        ->last_name->toBe('Doe')
        ->id_document_type->toBe(Identification::DRIVERS_LICENCE)
        ->id_document_number->toBe($tenant_1->id_document_number)
        ->and($contracts->first()->tenants->last())
        ->toBeInstanceOf(Tenant::class)
        ->first_name->toBe('Jane')
        ->last_name->toBe('Doe')
        ->id_document_type->toBe(Identification::ID_CARD)
        ->id_document_number->toBe($tenant_2->id_document_number);
});

test('a contract PDF can be instantiated', function () {
    $contract = Contract::factory()->create();
    $pdf = new GenerateContractRoz2017($contract);

    expect($pdf)->toBeInstanceOf(Fpdf::class);
});

test('a contract PDF can be generated', function () {
    $contract = Contract::factory()->create();
    $pdf = new GenerateContractRoz2017($contract, 'S');

    expect($pdf->generate())->toContain('PDF-1.4');
});

test('a contract PDF can be sent by email', function () {
    //    send email with attachement
})->skip();

test('a contract status can be changed to draft', function () {
    // set status
    // set document name
    // email document to owner?
})->skip();

test('a contract status can be changed to approval', function () {
    // update status
    // update document name
    // email document to owner and/or tenant ?
})->skip();

test('a contract status can be changed to final', function () {
    // update status
    // email document to owner
})->skip();

test('a contract status can be changed to active', function () {
    // update status via event?
})->skip();

test('a contract status can be changed to terminated', function () {
    // update status via user input
})->skip();

test('a contract next_expiration must be update when contract is still active', function () {
    // update status via event?
})->skip();
