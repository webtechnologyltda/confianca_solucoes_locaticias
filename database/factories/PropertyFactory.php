<?php

namespace Database\Factories;

use App\Enum\RentalStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'street_address' => fake()->streetAddress(),
            'number' => fake()->buildingNumber(),
            'complement' => fake()->secondaryAddress(),
            'city' => fake()->city(),
            'state' => fake()->stateAbbr(),
            'zip_code' => fake()->postcode(),
            'rental_price' => fake()->randomFloat(2, 500, 5000),
            'condo_fee' => fake()->optional()->randomFloat(2, 50, 500),
            'status' => fake()->randomElement([RentalStatus::AVAILABLE->value, RentalStatus::RENTED->value, RentalStatus::MAINTENANCE->value, RentalStatus::RESERVED->value]),
            'description' => fake()->paragraph(),
        ];
    }
}
