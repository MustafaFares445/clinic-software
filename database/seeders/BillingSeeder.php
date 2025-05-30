<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BillingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $billingTypes = [
            [
                'name' => 'فحص أولي',
                'amount_range' => [100, 300]
            ],
            [
                'name' => 'حشو تجميلي',
                'amount_range' => [400, 800]
            ],
            [
                'name' => 'علاج عصب',
                'amount_range' => [900, 1500]
            ],
            [
                'name' => 'خلع ضرس',
                'amount_range' => [200, 600]
            ],
            [
                'name' => 'تركيب تاج',
                'amount_range' => [1200, 2500]
            ],
            [
                'name' => 'جلسة تنظيف',
                'amount_range' => [150, 350]
            ],
            [
                'name' => 'أشعة سينية',
                'amount_range' => [80, 200]
            ]
        ];

        $patients = DB::table('patients')->pluck('id')->toArray();
        $clinics = DB::table('clinics')->pluck('id')->toArray();

        $billings = [];

        for ($i = 0; $i < 100; $i++) {
            $type = $billingTypes[array_rand($billingTypes)];
            $amount = rand($type['amount_range'][0], $type['amount_range'][1]);

            // Add random cents (0-99) to make amounts more realistic
            $amount += (rand(0, 99) / 100);

            $billings[] = [
                'id' => Str::uuid(),
                'patient_id' => $patients[array_rand($patients)],
                'clinic_id' => $clinics[array_rand($clinics)],
                'amount' => $amount,
                'date' => Carbon::now()->subDays(rand(0, 90))->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('billings')->insert($billings);
    }
}