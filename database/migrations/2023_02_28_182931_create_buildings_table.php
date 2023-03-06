<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained();
            $table->string('reference', 10)->unique();
            $table->string('name', 100);
            $table->string('description', 255)->nullable();
            $table->string('address', 100);
            $table->string('zipcode', 10);
            $table->string('city', 100);
            $table->year('year_construction')->nullable();
            $table->year('year_renovation')->nullable();
            $table->boolean('energy_label')->default(false);
            $table->integer('distance_public_transport')->nullable();
            $table->integer('distance_center')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buildings');
    }
};
