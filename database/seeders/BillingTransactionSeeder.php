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
                'model_id' => Reservation::query()->inRandomOrder()->first()->id,
                'model_type' => Reservation::class,
                'type' => 'in',
                'amount' => rand(1 , 100),
                'description' => 'وصف موجز'
            ]);
        }


        for($i = 0 ; $i <= 5 ; $i++){
            BillingTransaction::query()->create([
                'user_id' => User::query()->inRandomOrder()->first()->id,
                'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
                'model_id' => MedicalTransactions::query()->inRandomOrder()->first()->id,
                'model_type' => MedicalTransactions::class,
                'type' => 'in',
                'amount' => rand(1 , 100),
                'description' => 'وصف موجز'
            ]);
        }
    }
}
