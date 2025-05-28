<?php

namespace Database\Factories;

use App\Enum\AnalysisStatus;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RentalAnalysis>
 */
class RentalAnalysisFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => fake()->numberBetween(Tenant::count(), 10),
            'property_id' => fake()->numberBetween(Property::count(), 10),
            'status' => fake()->randomElement(AnalysisStatus::cases()),
            'credit_score' => fake()->optional()->randomFloat(2, 0, 1000),
            'observations' => fake()->text(),
            'analysis_document' => fake()->optional()->filePath(),
            'analysis_date' => Carbon::now()->format('Y-m-d'),
            'analyst_id' => fake()->numberBetween(User::count(), 10),
        ];
    }
}
