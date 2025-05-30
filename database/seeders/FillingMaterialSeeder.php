<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FillingMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fillingMaterials = [
            [
                'id' => Str::uuid(),
                'name' => 'حشوة ملغمية',
                'color' => 'فضي',
                'laboratory_id' => DB::table('laboratories')->first()->id,
                'clinic_id' => DB::table('clinics')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'حشوة مركبة',
                'color' => 'أبيض',
                'laboratory_id' => DB::table('laboratories')->first()->id,
                'clinic_id' => DB::table('clinics')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'حشوة زجاجية أيونومر',
                'color' => 'أبيض كريمي',
                'laboratory_id' => DB::table('laboratories')->first()->id,
                'clinic_id' => DB::table('clinics')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'حشوة سيراميك',
                'color' => 'لون السن',
                'laboratory_id' => DB::table('laboratories')->first()->id,
                'clinic_id' => DB::table('clinics')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'حشوة ذهبية',
                'color' => 'ذهبي',
                'laboratory_id' => DB::table('laboratories')->first()->id,
                'clinic_id' => DB::table('clinics')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('filling_materials')->insert($fillingMaterials);
    }
}