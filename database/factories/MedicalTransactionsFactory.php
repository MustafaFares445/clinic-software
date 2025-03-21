<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Medicine;
use App\Models\Record;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicalTransactions>
 */
class MedicalTransactionsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quantity' => $this->faker->numberBetween(1, 100),
            'type' => $this->faker->randomElement(['in', 'out']),
            'description' => $this->faker->sentence,
            'record_id' => Record::factory(),
            'clinic_id' => Clinic::factory(),
            'doctor_id' => User::factory(),
            'medicine_id' => Medicine::factory()
        ];
    }
}
