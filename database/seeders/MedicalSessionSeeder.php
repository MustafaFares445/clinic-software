<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\MedicalCase;
use Illuminate\Support\Str;
use App\Models\MedicalSession;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        for ($i = 0; $i < 10; $i++) {
            $date = Carbon::now()->subDays(rand(0, 30))->setTime(
                rand(8, 18), // Between 8 AM and 6 PM
                rand(0, 59)
            );

            MedicalSession::query()->create([
                'id' => Str::uuid(),
                'medical_case_id' => DB::table('medical_cases')->inRandomOrder()->first()->id,
                'date' => $date,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}