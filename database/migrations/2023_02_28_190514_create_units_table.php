<?php

use App\Enums\RentalCategory;
use App\Enums\RentalStatus;
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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 10)->unique();
            $table->foreignId('building_id')->constrained();
            //            $table->foreignId('kitchen_id')->constrained();
            //            $table->foreignId('laundryroom_id')->constrained();
            //            $table->foreignId('sanitation_id')->constrained();
            $table->foreignId('category')->default(RentalCategory::UNKNOWN->value);
            $table->foreignId('status')->default(RentalStatus::AVAILABLE->value);
            $table->string('description', 255)->nullable();
            $table->integer('surface')->nullable();
            $table->integer('price')->default(0);
            $table->integer('energy_costs_advanced')->nullable();
            $table->integer('service_charge_amount')->nullable();
            $table->boolean('service_charge')->default(false);
            $table->json('services')->default(new Expression('(JSON_ARRAY())'));
            $table->boolean('energy_costs_included')->default(false);
            $table->boolean('metering_electricity')->default(false);
            $table->boolean('metering_gas')->default(false);
            $table->boolean('metering_water')->default(false);
            $table->boolean('independent_living_space')->default(false);
            $table->boolean('shared_entrance')->default(false);
            $table->boolean('furnished')->default(false);
            $table->boolean('upholstered')->default(false);
            $table->integer('rooms')->nullable();
            $table->boolean('published')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
