<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word() . ' Plan',
            'description' => fake()->sentence(),
            'fixed_value' => fake()->randomFloat(2, 0, 1000),
            'percent_value' => fake()->randomFloat(2, 0, 100),
            'users_count' => fake()->numberBetween(1, 1000),
            'duration' => fake()->numberBetween(1, 12), 
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
