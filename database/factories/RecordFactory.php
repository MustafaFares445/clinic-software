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
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->uuid(),
            'patient_id' => Patient::factory(),
            'clinic_id' => Clinic::factory(),
            'reservation_id' => Reservation::factory(),
            'description' => fake()->paragraph(),
            'type' => fake()->randomElement(RecordTypes::cases())->value,
            'dateTime' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * Indicate that the record is for an appointment.
     */
    public function appointment(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => RecordTypes::APPOINTMENT->value,
        ]);
    }

    /**
     * Indicate that the record is for a surgery.
     */
    public function surgery(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => RecordTypes::SURGERY->value,
        ]);
    }

    /**
     * Indicate that the record is for an inspection.
     */
    public function inspection(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => RecordTypes::INSPECTION->value,
        ]);
    }

    /**
     * Indicate that the record has no reservation.
     */
    public function withoutReservation(): static
    {
        return $this->state(fn (array $attributes) => [
            'reservation_id' => null,
        ]);
    }
}
