<?php

namespace Database\Factories;

use App\Models\Specification;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Specification>
 */
class SpecificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Specification::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'name' => fake()->unique()->word(),
            'description' => fake()->optional()->paragraph(),
            'parent_id' => null, // This can be set manually when needed
            'created_at' => fake()->dateTimeBetween('-1 year'),
            'updated_at' => fake()->dateTimeBetween('-1 month'),
        ];
    }

    /**
     * Configure the factory to create a specification with a parent.
     */
    public function withParent(?Specification $parent = null): static
    {
        return $this->state(function (array $attributes) use ($parent) {
            return [
                'parent_id' => $parent ? $parent->id : Specification::factory(),
            ];
        });
    }
} 