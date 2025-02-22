<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Offer>
 */
class OfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $useFixedValue = fake()->boolean();
        $startDate = fake()->dateTimeBetween('-1 month', '+1 month');
        $endDate = fake()->dateTimeBetween($startDate, '+2 months');
        
        return [
            'id' => fake()->uuid(),
            'name' => fake()->words(3, true) . ' Offer',
            'start' => $startDate,
            'end' => $endDate,
            'fixed_value' => $useFixedValue ? fake()->numberBetween(10, 1000) * 100 : null, // Value in cents
            'percent_value' => !$useFixedValue ? fake()->numberBetween(5, 50) : null,
            'is_active' => fake()->boolean(70), // 70% chance of being active
        ];
    }
}
