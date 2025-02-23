<?php

namespace Database\Seeders;

use App\Enums\ReservationStatuses;
use App\Enums\ReservationTypes;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Reservation;
use App\Models\Specification;
use App\Models\User;
use Illuminate\Database\Seeder;

final class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reservation::query()->create([
            'start' => now(),
            'end' => now()->addHour(),
            'patient_id' => Patient::query()->inRandomOrder()->first()->id,
            'clinic_id' => Clinic::query()->first()->id,
            'doctor_id' => User::query()->inRandomOrder()->first()->id,
            'type' => ReservationTypes::APPOINTMENT,
            'status' => ReservationStatuses::CHECK,
        ]);

        Reservation::query()->create([
            'start' => now()->addDay(),
            'end' => now()->addDay()->addHour(),
            'patient_id' => Patient::query()->inRandomOrder()->first()->id,
            'clinic_id' => Clinic::query()->first()->id,
            'doctor_id' => User::query()->inRandomOrder()->first()->id,
            'specification_id' => Specification::query()->inRandomOrder()->first()->id,
            'type' => ReservationTypes::SURGERY,
            'status' => ReservationStatuses::INCOME,
        ]);

        Reservation::query()->create([
            'start' => now()->addDays(2),
            'end' => now()->addDays(2)->addHour(),
            'patient_id' => Patient::query()->inRandomOrder()->first()->id,
            'clinic_id' => Clinic::query()->first()->id,
            'doctor_id' => User::query()->inRandomOrder()->first()->id,
            'specification_id' => Specification::query()->inRandomOrder()->first()->id,
            'type' => ReservationTypes::INSPECTION,
            'status' => ReservationStatuses::DISMISS,
        ]);

        Reservation::query()->create([
            'start' => now()->addDays(2)->addHours(2),
            'end' => now()->addDays(2)->addHours(4),
            'patient_id' => Patient::query()->inRandomOrder()->first()->id,
            'clinic_id' => Clinic::query()->first()->id,
            'doctor_id' => User::query()->inRandomOrder()->first()->id,
            'specification_id' => Specification::query()->inRandomOrder()->first()->id,
            'type' => ReservationTypes::APPOINTMENT,
            'status' => ReservationStatuses::CANCELLED,
        ]);
    }
}
