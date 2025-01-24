<?php

namespace Database\Seeders;

use App\Enums\ReservationStatuses;
use App\Enums\ReservationTypes;
use App\Models\Clinic;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Reservation;
use App\Models\Specification;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reservation::query()->create([
            'start' => '2025-01-27 11:00:00',
            'end' => '2025-01-27 12:00:00',
            'patient_id' => Patient::query()->inRandomOrder()->first()->id,
            'clinic_id' => Clinic::query()->first()->id,
            'doctor_id' => User::query()->inRandomOrder()->first()->id,
            'type' => ReservationTypes::APPOINTMENT,
            'status' => ReservationStatuses::CHECK,
        ]);

        Reservation::query()->create([
            'start' => '2025-01-26 02:00:00',
            'end' => '2025-01-26 03:00:00',
            'patient_id' => Patient::query()->inRandomOrder()->first()->id,
            'clinic_id' => Clinic::query()->first()->id,
            'doctor_id' => User::query()->inRandomOrder()->first()->id,
            'specification_id' => Specification::query()->inRandomOrder()->first()->id,
            'type' => ReservationTypes::SURGERY,
            'status' => ReservationStatuses::INCOME,
        ]);

        Reservation::query()->create([
            'start' => '2025-01-25 02:00:00',
            'end' => '2025-01-25 03:00:00',
            'patient_id' => Patient::query()->inRandomOrder()->first()->id,
            'clinic_id' => Clinic::query()->first()->id,
            'doctor_id' => User::query()->inRandomOrder()->first()->id,
            'specification_id' => Specification::query()->inRandomOrder()->first()->id,
            'type' => ReservationTypes::INSPECTION,
            'status' => ReservationStatuses::DISMISS,
        ]);

        Reservation::query()->create([
            'start' => '2025-01-28 02:00:00',
            'end' => '2025-01-28 03:00:00',
            'patient_id' => Patient::query()->inRandomOrder()->first()->id,
            'clinic_id' => Clinic::query()->first()->id,
            'doctor_id' => User::query()->inRandomOrder()->first()->id,
            'specification_id' => Specification::query()->inRandomOrder()->first()->id,
            'type' =>  ReservationTypes::APPOINTMENT,
            'status' => ReservationStatuses::CANCELLED,
        ]);
    }
}
