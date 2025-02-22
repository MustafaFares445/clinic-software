<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::query()->create([
            'name' => 'الخطة البرونزية',
            'description' => 'وصف موجز',
            'fixed_value' => null,
            'percent_value' => 70,
            'users_count' => 2,
            'duration' => '10:00:00'
        ]);

        Plan::query()->create([
            'name' => 'الخطة الذهبية',
            'description' => 'وصف موجز',
            'fixed_value' => 100,
            'users_count' => 10,
            'duration' => '10:00:00'
        ]);

        Plan::query()->create([
            'name' => 'الخطة الألماسية',
            'description' => 'وصف موجز',
            'fixed_value' => 200,
            'users_count' => 20,
            'duration' => '10:00:00'
        ]);
    }
}
