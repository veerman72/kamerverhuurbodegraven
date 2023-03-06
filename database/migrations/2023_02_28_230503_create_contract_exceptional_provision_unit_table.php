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
        Schema::create('contract_exceptional_provision_unit', function (Blueprint $table) {
            $table->foreignId('unit_id');
            $table->foreignId('contract_exceptional_provision_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_exceptional_provision_unit');
    }
};
