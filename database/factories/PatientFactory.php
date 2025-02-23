<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
final class PatientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'firstName' => fake()->firstName(),
            'lastName' => fake()->lastName(),
            'phone' => fake()->phoneNumber(),
            'age' => fake()->numberBetween(1, 100),
            'fatherName' => fake()->firstName('male'),
            'motherName' => fake()->firstName('female'),
            'nationalNumber' => fake()->unique()->numerify('##########'),
            'address' => fake()->address(),
            'birth' => fake()->date(),
            'gender' => fake()->randomElement(['male', 'female']),
            'clinic_id' => Clinic::factory(),
            'notes' => fake()->optional()->paragraph(),
            'created_at' => fake()->dateTimeBetween('-1 year'),
            'updated_at' => fake()->dateTimeBetween('-1 year'),
        ];
    }
}
