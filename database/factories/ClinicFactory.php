<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Clinic>
 */
final class ClinicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'address' => $this->faker->address,
            'longitude' => $this->faker->longitude,
            'latitude' => $this->faker->latitude,
            'description' => $this->faker->sentence,
            'is_banned' => $this->faker->boolean,
            'type' => 'clinic',
            'start' => $this->faker->time,
            'end' => $this->faker->time,
        ];
    }
}
