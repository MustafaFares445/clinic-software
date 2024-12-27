<?php

namespace Database\Seeders;

use App\Models\Medicine;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicine = Medicine::query()->create([
            'name' => 'سيرجام',
            'description' => null,
        ]);

        $medicine->medicineTransaction()->create([
            'clinic_id' => 1,
            'quantity' => 10
        ]);
        $medicine->transactions()->create([
            'clinic_id' => 1,
            'type' => 'outcome',
            'amount' => 10,
            'description' => null
        ]);
        $medicine->categories()->sync([1]);

        //**************************************//
        $medicine2 = Medicine::query()->create([
            'name' => 'سيتامول',
            'description' => null,
        ]);

        $medicine2->medicineTransaction()->create([
            'clinic_id' => 1,
            'quantity' => 5
        ]);
        $medicine2->transactions()->create([
            'clinic_id' => 1,
            'type' => 'outcome',
            'amount' => 5,
            'description' => null
        ]);
        $medicine2->categories()->sync([1]);

        //**************************************//
        $medicine3 = Medicine::query()->create([
            'name' => 'اوراكورت',
            'description' => null,
        ]);

        $medicine3->medicineTransaction()->create([
            'clinic_id' => 1,
            'quantity' => 3
        ]);
        $medicine3->transactions()->create([
            'clinic_id' => 1,
            'type' => 'outcome',
            'amount' => 3,
            'description' => null
        ]);
        $medicine3->categories()->sync([1]);

        //**************************************//
        $medicine4 =  Medicine::query()->create([
            'name' => 'بروفين',
            'description' => null,
        ]);

        $medicine4->medicineTransaction()->create([
            'clinic_id' => 1,
            'quantity' => 2
        ]);
        $medicine4->transactions()->create([
            'clinic_id' => 1,
            'type' => 'outcome',
            'amount' => 2,
            'description' => null
        ]);
        $medicine4->categories()->sync([2]);

        //**************************************//
        $medicine5 = Medicine::query()->create([
            'name' => 'رسيف',
            'description' => null,
        ]);

        $medicine5->medicineTransaction()->create([
            'clinic_id' => 1,
            'quantity' => 8
        ]);
        $medicine5->transactions()->create([
            'clinic_id' => 1,
            'type' => 'outcome',
            'amount' => 8,
            'description' => null
        ]);
        $medicine5->categories()->sync([2]);
    }
}
