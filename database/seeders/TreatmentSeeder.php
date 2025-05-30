<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TreatmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if clinics exist
        $clinicIds = DB::table('clinics')->pluck('id')->toArray();

        if (empty($clinicIds)) {
            $this->command->error('لم يتم العثور على عيادات. يرجى إنشاء العيادات أولاً.');
            return;
        }

        $treatments = [
            [
                'name' => 'حشو تجميلي',
                'color' => '#4CAF50', // Green
            ],
            [
                'name' => 'حشو عادي',
                'color' => '#8BC34A', // Light Green
            ],
            [
                'name' => 'تنظيف أسنان',
                'color' => '#00BCD4', // Cyan
            ],
            [
                'name' => 'تبييض الأسنان',
                'color' => '#FFFFFF', // White
            ],
            [
                'name' => 'تقويم الأسنان',
                'color' => '#FFC107', // Amber
            ],
            [
                'name' => 'خلع ضرس',
                'color' => '#F44336', // Red
            ],
            [
                'name' => 'علاج عصب',
                'color' => '#9C27B0', // Purple
            ],
            [
                'name' => 'تركيب تاج',
                'color' => '#FF9800', // Orange
            ],
            [
                'name' => 'زراعة أسنان',
                'color' => '#607D8B', // Blue Grey
            ],
            [
                'name' => 'فحص دوري',
                'color' => '#2196F3', // Blue
            ],
            [
                'name' => 'علاج اللثة',
                'color' => '#E91E63', // Pink
            ],
            [
                'name' => 'تركيب جسر',
                'color' => '#795548', // Brown
            ],
        ];

        foreach ($treatments as $treatment) {
            DB::table('treatments')->insert([
                'id' => Str::uuid(),
                'name' => $treatment['name'],
                'color' => $treatment['color'],
                'clinic_id' => $clinicIds[array_rand($clinicIds)], // Assign random clinic
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}