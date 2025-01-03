<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Medicine;
use App\Models\MedicineTransaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicineTransactionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MedicineTransaction::query()->create([
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'medicine_id' => Medicine::query()->inRandomOrder()->first()->id,
            'quantity' => 10
        ]);
    }
}
