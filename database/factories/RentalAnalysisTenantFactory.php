<?php

namespace Database\Factories;

use App\Models\RentalAnalysis;
use App\Models\RentalAnalysisTenant;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class RentalAnalysisTenantFactory extends Factory
{
    protected $model = RentalAnalysisTenant::class;

    public function definition(): array
    {
        return [
            'rental_analysis_id' => $this->faker->numberBetween(1, RentalAnalysis::count()),
            'tenant_id' => $this->faker->numberBetween(1, Tenant::count()),
        ];
    }
}
