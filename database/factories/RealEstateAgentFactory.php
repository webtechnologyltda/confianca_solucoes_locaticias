<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class RealEstateAgentFactory extends Factory
{

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'creci' => fake()->numerify('###########'),
        ];
    }
}
