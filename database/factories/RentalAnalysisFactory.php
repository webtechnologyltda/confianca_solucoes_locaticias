<?php

namespace Database\Factories;

use App\Enum\AnalysisStatus;
use App\Models\Property;
use App\Models\RealEstateAgent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;


class RentalAnalysisFactory extends Factory
{
        public function definition(): array
    {
        return [
            'property_id' => fake()->numberBetween(Property::count(), 10),
            'status' => fake()->randomElement(AnalysisStatus::cases()),
            'credit_score' => fake()->optional()->randomFloat(2, 0, 1000),
            'tax' => fake()->numberBetween(100,1000),
            'other_tax' => fake()->numberBetween(100,2000),
            'house_rental_value' => fake()->numberBetween(100000,200000),
            'observations' => fake()->text(),
            'analysis_date' => Carbon::now()->format('Y-m-d'),
            'analyst_id' => fake()->numberBetween(User::count(), 10),
            'real_estate_agent_id' => fake()->numberBetween(RealEstateAgent::count(), 10),
        ];

    }

}
