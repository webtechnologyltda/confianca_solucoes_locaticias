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
            $table->foreignId('tenant_id')->constrained();
            $table->foreignId('property_id')->constrained();
            $table->string('status')->default(AnalysisStatus::PENDING)->nullable();
            $table->decimal('credit_score', 5, 2)->nullable();
            $table->text('observations')->nullable();
            $table->string('analysis_document')->nullable();
            $table->datetime('analysis_date')->nullable();
            $table->foreignId('analyst_id')->constrained('users');
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
