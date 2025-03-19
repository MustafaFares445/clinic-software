<?php

namespace Database\Factories;

use App\Enums\RecordTypes;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Record>
 */
final class RecordFactory extends Factory
{
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'clinic_id' => Clinic::factory(),
            'reservation_id' => Reservation::factory(),
            'description' => $this->faker->paragraph,
            'type' => $this->faker->randomElement(RecordTypes::cases()),
            'dateTime' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
