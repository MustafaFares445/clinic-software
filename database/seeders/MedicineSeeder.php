<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Medicine;
use App\Models\Specification;
use App\Models\User;
use Illuminate\Database\Seeder;

final class MedicineSeeder extends Seeder
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
            'quantity' => 10,
            'doctor_id' => User::query()->inRandomOrder()->first()->id,
            'type' => 'in'
        ]);


        $medicine->transactions()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'type' => 'outcome',
            'quantity' => 10,
            'description' => null,
            'type' => 'in',
            'doctor_id' => User::query()->inRandomOrder()->first()->id,
        ]);
        $medicine->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);

        // **************************************//
        $medicine2 = Medicine::query()->create([
            'name' => 'سيتامول',
            'description' => null,
        ]);

        $medicine2->transactions()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'quantity' => 5,
            'type' => 'in',
            'doctor_id' => User::query()->inRandomOrder()->first()->id,
        ]);

        $medicine2->transactions()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'type' => 'out',
            'quantity' => 5,
            'description' => null,
            'doctor_id' => User::query()->inRandomOrder()->first()->id,
        ]);

        $medicine2->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);

        // **************************************//
        $medicine3 = Medicine::query()->create([
            'name' => 'اوراكورت',
            'description' => null,
        ]);

        $medicine3->transactions()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'quantity' => 3,
            'type' => 'out',
            'doctor_id' => User::query()->inRandomOrder()->first()->id,
        ]);
        $medicine3->transactions()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'type' => 'outcome',
            'quantity' => 3,
            'description' => null,
            'type' => 'out',
            'doctor_id' => User::query()->inRandomOrder()->first()->id,
        ]);
        $medicine3->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);

        // **************************************//
        $medicine4 = Medicine::query()->create([
            'name' => 'بروفين',
            'description' => null,
        ]);

        $medicine4->transactions()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'quantity' => 2,
            'type' => 'out',
            'doctor_id' => User::query()->inRandomOrder()->first()->id,
        ]);
        $medicine4->transactions()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'type' => 'out',
            'quantity' => 2,
            'description' => null,
            'doctor_id' => User::query()->inRandomOrder()->first()->id,
        ]);
        $medicine4->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);

        // **************************************//
        $medicine5 = Medicine::query()->create([
            'name' => 'رسيف',
            'description' => null,
        ]);

        $medicine5->transactions()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'quantity' => 8,
            'type' => 'out',
            'doctor_id' => User::query()->inRandomOrder()->first()->id,
        ]);
        $medicine5->transactions()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'type' => 'out',
            'quantity' => 8,
            'description' => null,
            'doctor_id' => User::query()->inRandomOrder()->first()->id,
        ]);
        $medicine5->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);
    }
}
