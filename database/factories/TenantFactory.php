<?php

namespace Database\Factories;

use App\Enum\MaritalStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class TenantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'cpf' => fake()->numerify('###########'),
            'birth_date' => fake()->date('Y-m-d'),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'monthly_income' => fake()->numberBetween(10000, 50000),
            'occupation' => fake()->jobTitle(),
            'marital_status' => fake()->randomElement([MaritalStatus::SINGLE->value, MaritalStatus::MARRIED->value, MaritalStatus::DIVORCED->value, MaritalStatus::WIDOWED->value]),
            'additional_notes' => fake()->text(),
        ];
    }
}
