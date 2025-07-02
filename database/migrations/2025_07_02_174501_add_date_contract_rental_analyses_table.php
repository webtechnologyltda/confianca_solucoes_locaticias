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
        Schema::table('rental_analyses', function (Blueprint $table) {
            $table->dateTime('contract_signature_date')->nullable();
            $table->dateTime('contract_renewal_date')->nullable();
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('rental_analyses', ['contract_signature_date', 'contract_renewal_date']);
    }
};
