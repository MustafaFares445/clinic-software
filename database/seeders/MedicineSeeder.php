<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Medicine;
use App\Models\Specification;
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
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'quantity' => 10
        ]);
        $medicine->transactions()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'type' => 'outcome',
            'amount' => 10,
            'description' => null
        ]);
        $medicine->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);

        //**************************************//
        $medicine2 = Medicine::query()->create([
            'name' => 'سيتامول',
            'description' => null,
        ]);

        $medicine2->medicineTransaction()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'quantity' => 5
        ]);
        $medicine2->transactions()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'type' => 'outcome',
            'amount' => 5,
            'description' => null
        ]);
        $medicine2->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);

        //**************************************//
        $medicine3 = Medicine::query()->create([
            'name' => 'اوراكورت',
            'description' => null,
        ]);

        $medicine3->medicineTransaction()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'quantity' => 3
        ]);
        $medicine3->transactions()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'type' => 'outcome',
            'amount' => 3,
            'description' => null
        ]);
        $medicine3->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);

        //**************************************//
        $medicine4 =  Medicine::query()->create([
            'name' => 'بروفين',
            'description' => null,
        ]);

        $medicine4->medicineTransaction()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'quantity' => 2
        ]);
        $medicine4->transactions()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'type' => 'outcome',
            'amount' => 2,
            'description' => null
        ]);
        $medicine4->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);

        //**************************************//
        $medicine5 = Medicine::query()->create([
            'name' => 'رسيف',
            'description' => null,
        ]);

        $medicine5->medicineTransaction()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'quantity' => 8
        ]);
        $medicine5->transactions()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'type' => 'outcome',
            'amount' => 8,
            'description' => null
        ]);
        $medicine5->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);
    }
}
