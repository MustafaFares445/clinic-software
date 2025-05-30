<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $records = [
            // Dental Cases
            [
                'id' => Str::uuid(),
                'description' => 'حشو عصب للضرس الأول السفلي مع استخدام حشوة مؤقتة بسبب وجود التهاب',
                'type' => 'out',
                'clinic_id' => DB::table('clinics')->first()->id,
                'patient_id' => DB::table('patients')->inRandomOrder()->first()->id,
                'tooth_id' => DB::table('teeth')->inRandomOrder()->first()->id,
                'treatment_id' => DB::table('treatments')->inRandomOrder()->first()->id,
                'filling_material_id' => DB::table('filling_materials')->inRandomOrder()->first()->id,
                'medical_session_id' => DB::table('medical_sessions')->inRandomOrder()->first()->id,
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'id' => Str::uuid(),
                'description' => 'تركيب حشوة دائمة من السيراميك للضرس الثاني العلوي بعد انتهاء علاج العصب',
                'type' => 'in',
                'clinic_id' => DB::table('clinics')->first()->id,
                'patient_id' => DB::table('patients')->inRandomOrder()->first()->id,
                'tooth_id' => DB::table('teeth')->inRandomOrder()->first()->id,
                'treatment_id' => DB::table('treatments')->inRandomOrder()->first()->id,
                'filling_material_id' => DB::table('filling_materials')->inRandomOrder()->first()->id,
                'medical_session_id' => DB::table('medical_sessions')->inRandomOrder()->first()->id,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],

            // Restorative Cases
            [
                'id' => Str::uuid(),
                'description' => 'إزالة التسوس من الناب العلوي الأيمن وحشوه بحشوة بيضاء مركبة',
                'type' => 'out',
                'clinic_id' => DB::table('clinics')->first()->id,
                'patient_id' => DB::table('patients')->inRandomOrder()->first()->id,
                'tooth_id' => DB::table('teeth')->inRandomOrder()->first()->id,
                'treatment_id' => DB::table('treatments')->inRandomOrder()->first()->id,
                'filling_material_id' => DB::table('filling_materials')->inRandomOrder()->first()->id,
                'medical_session_id' => DB::table('medical_sessions')->inRandomOrder()->first()->id,
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(7),
            ],

            // Surgical Cases
            [
                'id' => Str::uuid(),
                'description' => 'خلع جراحي لضرس العقل السفلي الأيسر المطمور مع وضع غرز جراحية',
                'type' => 'out',
                'clinic_id' => DB::table('clinics')->first()->id,
                'patient_id' => DB::table('patients')->inRandomOrder()->first()->id,
                'tooth_id' => DB::table('teeth')->inRandomOrder()->first()->id,
                'treatment_id' => DB::table('treatments')->inRandomOrder()->first()->id,
                'filling_material_id' => null,
                'medical_session_id' => DB::table('medical_sessions')->inRandomOrder()->first()->id,
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10),
            ],

            // Follow-up Cases
            [
                'id' => Str::uuid(),
                'description' => 'فحص متابعة بعد أسبوع من خلع ضرس العقل، الجرح يلتئم بشكل جيد',
                'type' => 'in',
                'clinic_id' => DB::table('clinics')->first()->id,
                'patient_id' => DB::table('patients')->inRandomOrder()->first()->id,
                'tooth_id' => DB::table('teeth')->inRandomOrder()->first()->id,
                'treatment_id' => DB::table('treatments')->inRandomOrder()->first()->id,
                'filling_material_id' => null,
                'medical_session_id' => DB::table('medical_sessions')->inRandomOrder()->first()->id,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],

            // Pediatric Cases
            [
                'id' => Str::uuid(),
                'description' => 'حشو فضة للضرس اللبني الثاني السفلي الأيمن عند طفل عمره 6 سنوات',
                'type' => 'out',
                'clinic_id' => DB::table('clinics')->first()->id,
                'patient_id' => DB::table('patients')->inRandomOrder()->first()->id,
                'tooth_id' => DB::table('teeth')->inRandomOrder()->first()->id,
                'treatment_id' => DB::table('treatments')->inRandomOrder()->first()->id,
                'filling_material_id' => DB::table('filling_materials')->inRandomOrder()->first()->id,
                'medical_session_id' => DB::table('medical_sessions')->inRandomOrder()->first()->id,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
        ];

        DB::table('records')->insert($records);
    }
}