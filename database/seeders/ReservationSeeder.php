<?php

namespace Database\Seeders;

use App\Enums\ReservationStatuses;
use App\Enums\ReservationTypes;
use App\Models\Reservation;
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
            'start' => '2024-12-06 11:00:00',
            'end' => '2024-12-06 12:00:00',
            'patient_id' => 1,
            'clinic_id' => 1,
            'doctor_id' => 3,
            'type' => ReservationTypes::APPOINTMENT,
            'status' => ReservationStatuses::CHECK,
        ]);

        Reservation::query()->create([
            'start' => '2024-12-07 02:00:00',
            'end' => '2024-12-06 03:00:00',
            'patient_id' => 1,
            'clinic_id' => 1,
            'doctor_id' => 3,
            'type' => ReservationTypes::SURGERY,
            'status' => ReservationStatuses::INCOME,
        ]);

        Reservation::query()->create([
            'start' => '2024-12-07 02:00:00',
            'end' => '2024-12-06 03:00:00',
            'patient_id' => 1,
            'clinic_id' => 1,
            'doctor_id' => 3,
            'type' => ReservationTypes::INSPECTION,
            'status' => ReservationStatuses::DISMISS,
        ]);

        Reservation::query()->create([
            'start' => '2024-12-04 02:00:00',
            'end' => null,
            'patient_id' => 2,
            'clinic_id' => 1,
            'doctor_id' => 3,
            'type' =>  ReservationTypes::APPOINTMENT,
            'status' => ReservationStatuses::CANCELLED,
        ]);
    }
}
