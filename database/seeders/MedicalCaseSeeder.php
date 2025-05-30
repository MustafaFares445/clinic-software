<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MedicalCaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cases = [
            [
                'id' => Str::uuid(),
                'name' => 'حالة تسوس متقدم',
                'description' => 'مريض يعاني من تسوس متقدم في الضرس الأول العلوي مع التهاب اللثة المحيط، يحتاج إلى علاج عصب وحشوة دائمة.',
                'clinic_id' => DB::table('clinics')->first()->id,
                'patient_id' => DB::table('patients')->inRandomOrder()->first()->id,
                'created_by_id' => DB::table('users')->first()->id,
                'total' => 1200.50,
                'date' => Carbon::now()->subDays(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'تقويم أسنان',
                'description' => 'حالة اعوجاج في الأسنان الأمامية تحتاج إلى تقويم ثابت لمدة سنتين مع متابعة شهرية.',
                'clinic_id' => DB::table('clinics')->first()->id,
                'patient_id' => DB::table('patients')->inRandomOrder()->first()->id,
                'created_by_id' => DB::table('users')->first()->id,
                'total' => 8000.00,
                'date' => Carbon::now()->subDays(15),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'خلع ضرس العقل',
                'description' => 'ضرس العقل السفلي مطمور بشكل جانبي ويسبب آلاماً شديدة، يحتاج إلى خلع جراحي تحت التخدير الموضعي.',
                'clinic_id' => DB::table('clinics')->first()->id,
                'patient_id' => DB::table('patients')->inRandomOrder()->first()->id,
                'created_by_id' => DB::table('users')->first()->id,
                'total' => 750.25,
                'date' => Carbon::now()->subDays(5),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'تركيب تاج زركونيوم',
                'description' => 'تركيب تاج زركونيوم للضرس الثاني السفلي بعد إتمام علاج العصب وحشوة الأساس.',
                'clinic_id' => DB::table('clinics')->first()->id,
                'patient_id' => DB::table('patients')->inRandomOrder()->first()->id,
                'created_by_id' => DB::table('users')->first()->id,
                'total' => 2000.00,
                'date' => Carbon::now()->subDays(3),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'تنظيف وتبييض الأسنان',
                'description' => 'جلسة تنظيف أسنان متكاملة مع تبييض بالليزر للحصول على ابتسامة أكثر إشراقاً.',
                'clinic_id' => DB::table('clinics')->first()->id,
                'patient_id' => DB::table('patients')->inRandomOrder()->first()->id,
                'created_by_id' => DB::table('users')->first()->id,
                'total' => 1500.75,
                'date' => Carbon::now()->subDays(7),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'علاج دواعم السن',
                'description' => 'حالة التهاب دواعم السن المتقدم تحتاج إلى عدة جلسات تنظيف عميق وتقليح الجذور.',
                'clinic_id' => DB::table('clinics')->first()->id,
                'patient_id' => DB::table('patients')->inRandomOrder()->first()->id,
                'created_by_id' => DB::table('users')->first()->id,
                'total' => 3000.00,
                'date' => Carbon::now()->subDays(20),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('medical_cases')->insert($cases);
    }
}