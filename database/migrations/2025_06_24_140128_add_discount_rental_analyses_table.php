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
            $table->integer('discount_month')->nullable();
            $table->integer('discount_year')->nullable();
            $table->boolean('has_manual_discount')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('rental_analyses', ['discount_month', 'discount_year', 'has_manual_discount']);
    }
};
