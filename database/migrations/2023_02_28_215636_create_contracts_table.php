<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->uuid('reference')->index();
            $table->foreignId('unit_id')->constrained();
            $table->dateTime('document');
            $table->date('start');
            $table->date('end');
            $table->date('next_expiration');
            $table->integer('notice_period');
            $table->integer('duration');
            $table->integer('status'); // ENUM ??
            $table->integer('price');
            $table->integer('deposit');
            $table->integer('energy_costs_advanced')->nullable();
            $table->integer('service_charge_amount')->nullable();
            $table->boolean('service_charge')->default(false);
            $table->json('services')->default(new Expression('(JSON_ARRAY())'));
            $table->boolean('energy_costs_included')->default(false);
            $table->boolean('metering_electricity')->default(false);
            $table->boolean('metering_gas')->default(false);
            $table->boolean('metering_water')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
