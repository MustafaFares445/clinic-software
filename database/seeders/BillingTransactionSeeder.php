<?php

namespace Database\Seeders;

use App\Models\BillingTransaction;
use App\Models\Clinic;
use App\Models\Equipment;
use App\Models\MedicalTransactions;
use App\Models\Medicine;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Seeder;

class BillingTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 0 ; $i <= 10 ; $i++){
            BillingTransaction::query()->create([
                'user_id' => User::query()->inRandomOrder()->first()->id,
                'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
                'patient_id' => Patient::query()->inRandomOrder()->first()->id,
                'reservation_id' => Reservation::query()->inRandomOrder()->first()->id,
                'type' => 'paid',
                'amount' => rand(1 , 100),
                'description' => 'وصف موجز'
            ]);
        }


        for($i = 0 ; $i <= 5 ; $i++){
            BillingTransaction::query()->create([
                'user_id' => User::query()->inRandomOrder()->first()->id,
                'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
               'patient_id' => Patient::query()->inRandomOrder()->first()->id,
                'reservation_id' => Reservation::query()->inRandomOrder()->first()->id,
                'type' => 'recorded',
                'amount' => rand(1 , 100),
                'description' => 'وصف موجز'
            ]);
        }
    }
}
