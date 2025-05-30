<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Patient;
use Illuminate\Support\Str;
use App\Models\MedicalSession;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class MedicalSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if we have patients and clinics
        if (Patient::count() === 0 || Clinic::count() === 0) {
            $this->command->error('يجب وجود مرضى وعيادات أولاً!');
            return;
        }

        $sessions = [];

        // Generate sessions for the last 30 days
        for ($i = 0; $i < 50; $i++) {
            $patient = Patient::inRandomOrder()->first();
            $clinic = Clinic::inRandomOrder()->first();

            $date = Carbon::now()->subDays(rand(0, 30))->setTime(
                rand(8, 18), // Between 8 AM and 6 PM
                rand(0, 59)
            );

            $sessions[] = [
                'id' => Str::uuid(),
                'patient_id' => $patient->id,
                'clinic_id' => $clinic->id,
                'date' => $date,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Batch insert for better performance
        foreach (array_chunk($sessions, 20) as $chunk) {
            MedicalSession::query()->insert($chunk);
        }
    }
}