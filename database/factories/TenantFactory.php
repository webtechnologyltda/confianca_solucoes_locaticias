<?php

namespace Database\Factories;

use App\Enum\MaritalStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant>
 */
class TenantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'cpf' => fake()->numerify('###########'),
            'rg' => fake()->numerify('#########'),
            'birth_date' => fake()->date('Y-m-d'),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'monthly_income' => fake()->randomFloat(2, 1000, 10000),
            'occupation' => fake()->jobTitle(),
            'marital_status' => fake()->randomElement([MaritalStatus::SINGLE->value, MaritalStatus::MARRIED->value, MaritalStatus::DIVORCED->value, MaritalStatus::WIDOWED->value]),
            'additional_notes' => fake()->text(),
        ];
    }
}
