<?php

use App\Enum\AnalysisStatus;
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
        Schema::create('rental_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained();
            $table->string('status')->default(AnalysisStatus::PENDING)->nullable();
            $table->integer('credit_score')->nullable();
            $table->integer('tax')->nullable();
            $table->integer('other_tax')->nullable();
            $table->integer('house_rental_value')->nullable();
            $table->text('observations')->nullable();
            $table->datetime('analysis_date')->nullable();
            $table->foreignId('analyst_id')->constrained('users');
            $table->foreignId('real_estate_agent_id')->constrained('real_estate_agents');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_analyses');
    }
};
