<?php

namespace Database\Seeders;

use Database\Factories\RealEstateAgentFactory;
use Illuminate\Database\Seeder;

class RealEstateAgentSeeder extends Seeder
{
    public function run(): void
    {
        RealEstateAgentFactory::new()->count(10)->create();
    }
}
