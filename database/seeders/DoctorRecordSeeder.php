<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DoctorRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all doctors (users with doctor role)
        $doctors = DB::table('users')
            ->pluck('id')
            ->toArray();

        // Get all records
        $records = DB::table('records')
            ->pluck('id')
            ->toArray();

        // Create 50 random doctor-record associations
        $doctorRecords = [];
        $usedPairs = [];

        for ($i = 0; $i < 50; $i++) {
            $doctorId = $doctors[array_rand($doctors)];
            $recordId = $records[array_rand($records)];

            // Ensure unique doctor-record pairs
            $pairKey = $doctorId . '-' . $recordId;
            if (!in_array($pairKey, $usedPairs)) {
                $doctorRecords[] = [
                    'id' => Str::uuid(),
                    'doctor_id' => $doctorId,
                    'record_id' => $recordId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $usedPairs[] = $pairKey;
            }
        }

        DB::table('doctor_record')->insert($doctorRecords);
    }
}