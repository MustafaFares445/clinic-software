<?php

namespace Database\Factories;

use App\Enums\ReservationStatuses;
use App\Enums\ReservationTypes;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Specification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('now', '+1 month');
        $end = (clone $start)->modify('+30 minutes');
        
        return [
            'id' => fake()->uuid(),
            'start' => $start,
            'end' => $end,
            'patient_id' => Patient::factory(),
            'clinic_id' => Clinic::factory(),
            'doctor_id' => User::factory(),
            'specification_id' => Specification::factory(),
            'type' => fake()->randomElement(ReservationTypes::cases())->value,
            'status' => fake()->randomElement(ReservationStatuses::cases())->value,
        ];
    }

    /**
     * Indicate that the reservation is for an appointment.
     */
    public function appointment(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => ReservationTypes::APPOINTMENT->value,
        ]);
    }

    /**
     * Indicate that the reservation is for a surgery.
     */
    public function surgery(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => ReservationTypes::SURGERY->value,
        ]);
    }

    /**
     * Indicate that the reservation is for an inspection.
     */
    public function inspection(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => ReservationTypes::INSPECTION->value,
        ]);
    }
}
