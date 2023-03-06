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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('last_name', 255);
            $table->string('prefix', 30)->nullable();
            $table->string('first_name', 255);
            $table->string('place_of_birth', 100);
            $table->date('date_of_birth');
            $table->string('address', 100);
            $table->string('zipcode', 10);
            $table->string('city', 100);
            $table->string('email', 255);
            $table->string('phone', 30);
            $table->string('employer', 255)->nullable();
            $table->integer('id_document_type');
            $table
                ->string('id_document_number', 30)
                ->nullable()
                ->unique();
            $table
                ->integer('social_number')
                ->nullable()
                ->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
