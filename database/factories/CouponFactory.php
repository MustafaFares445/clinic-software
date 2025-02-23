<?php

namespace Database\Factories;

use App\Models\Coupon;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
final class CouponFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Coupon::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->uuid(),
            'fixed_value' => fake()->numberBetween(1000, 50000), // Values in cents
            'percent_value' => fake()->numberBetween(5, 50), // Percentage between 5% and 50%
            'expire_at' => fake()->dateTimeBetween('now', '+6 months'),
            'plan_id' => Plan::factory(),
            'used_number' => fake()->numberBetween(1, 100),
            'is_active' => fake()->boolean(80), // 80% chance of being active
        ];
    }

    /**
     * Indicate that the coupon is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expire_at' => fake()->dateTimeBetween('-6 months', '-1 day'),
        ]);
    }

    /**
     * Indicate that the coupon is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
