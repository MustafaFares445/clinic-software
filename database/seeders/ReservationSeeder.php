<?php

namespace Database\Seeders;

use App\Enums\ReservationStatuses;
use App\Enums\ReservationTypes;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = DB::table('patients')->pluck('id')->toArray();
        $clinics = DB::table('clinics')->pluck('id')->toArray();
        $doctors = DB::table('users')->pluck('id')->toArray();
        $medicalCases = DB::table('medical_cases')->pluck('id')->toArray();

        $reservationTypes = array_column(ReservationTypes::cases(), 'value');
        $reservationStatuses = array_column(ReservationStatuses::cases(), 'value');

        $reservations = [];

        // Generate 50 random reservations
        for ($i = 0; $i < 50; $i++) {
            $startDate = Carbon::now()
                ->addDays(rand(-30, 30))  // -30 to +30 days from now
                ->addHours(rand(8, 18))   // Between 8AM and 6PM
                ->addMinutes(rand(0, 12) * 5); // In 5-minute increments

            $duration = [15, 30, 45, 60][rand(0, 3)]; // Random duration
            $endDate = (clone $startDate)->addMinutes($duration);

            $reservations[] = [
                'id' => Str::uuid(),
                'start' => $startDate,
                'end' => $endDate,
                'patient_id' => $patients[array_rand($patients)],
                'clinic_id' => $clinics[array_rand($clinics)],
                'doctor_id' => $doctors[array_rand($doctors)],
                'medical_case_id' => rand(0, 1) ? $medicalCases[array_rand($medicalCases)] : null, // 50% chance of having medical case
                'type' => $reservationTypes[array_rand($reservationTypes)],
                'status' => $reservationStatuses[array_rand($reservationStatuses)],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('reservations')->insert($reservations);
    }
}