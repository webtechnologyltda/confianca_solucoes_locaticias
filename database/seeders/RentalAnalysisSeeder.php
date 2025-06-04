<?php

namespace Database\Seeders;

use App\Models\RentalAnalysis;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class RentalAnalysisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RentalAnalysis::factory(10)->hasAttached(Tenant::factory(rand(1, 10)))->create();
    }
}
