<?php

namespace Database\Seeders;

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
            'clinic_id' => 1,
            'medicine_id' => 1,
            'quantity' => 10
        ]);
    }
}
