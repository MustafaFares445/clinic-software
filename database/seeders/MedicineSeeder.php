<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Medicine;
use App\Models\Specification;
use Illuminate\Database\Seeder;
use App\Enums\TransactionFromTypes;
use App\Models\User;

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

        $medicine->transactions()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'amount' => 10,
            'user_id' => User::query()->inRandomOrder()->first()->id,
        ]);
        $medicine->transactions()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'type' => 'outcome',
            'amount' => 10,
            'description' => null,
            'type' => 'in',
            'user_id' => User::query()->inRandomOrder()->first()->id,
        ]);
        $medicine->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);

        //**************************************//
        $medicine2 = Medicine::query()->create([
            'name' => 'سيتامول',
            'description' => null,
        ]);

        $medicine2->transactions()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'amount' => 5,
            'from' => TransactionFromTypes::MEDICINE,
            'type' => 'in',
            'user_id' => User::query()->inRandomOrder()->first()->id,
        ]);
        $medicine2->transactions()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'type' => 'out',
            'amount' => 5,
            'from' => TransactionFromTypes::MEDICINE,
            'description' => null,
            'user_id' => User::query()->inRandomOrder()->first()->id,
        ]);
        $medicine2->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);

        //**************************************//
        $medicine3 = Medicine::query()->create([
            'name' => 'اوراكورت',
            'description' => null,
        ]);

        $medicine3->transactions()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'amount' => 3,
            'from' => TransactionFromTypes::MEDICINE,
            'type' => 'out',
            'user_id' => User::query()->inRandomOrder()->first()->id,
        ]);
        $medicine3->transactions()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'type' => 'outcome',
            'amount' => 3,
            'description' => null,
            'from' => TransactionFromTypes::MEDICINE,
            'type' => 'out',
            'user_id' => User::query()->inRandomOrder()->first()->id,
        ]);
        $medicine3->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);

        //**************************************//
        $medicine4 =  Medicine::query()->create([
            'name' => 'بروفين',
            'description' => null,
        ]);

        $medicine4->transactions()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'amount' => 2,
            'from' => TransactionFromTypes::MEDICINE,
            'type' => 'out',
            'user_id' => User::query()->inRandomOrder()->first()->id,
        ]);
        $medicine4->transactions()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'type' => 'out',
            'amount' => 2,
            'description' => null,
            'from' => TransactionFromTypes::MEDICINE,
            'user_id' => User::query()->inRandomOrder()->first()->id,
        ]);
        $medicine4->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);

        //**************************************//
        $medicine5 = Medicine::query()->create([
            'name' => 'رسيف',
            'description' => null,
        ]);

        $medicine5->transactions()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'amount' => 8,
            'type' => 'in',
            'from' => TransactionFromTypes::MEDICINE,
            'user_id' => User::query()->inRandomOrder()->first()->id,
        ]);
        $medicine5->transactions()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'type' => 'in',
            'amount' => 8,
            'description' => null,
            'from' => TransactionFromTypes::MEDICINE,
            'user_id' => User::query()->inRandomOrder()->first()->id,
        ]);
        $medicine5->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);
    }
}
