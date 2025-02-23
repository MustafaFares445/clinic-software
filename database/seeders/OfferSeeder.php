<?php

namespace Database\Seeders;

use App\Models\Offer;
use Illuminate\Database\Seeder;

final class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Offer::query()->create([
            'name' => 'حسم رأس السنة',
            'start' => '2024-12-01 12:00:00',
            'end' => '2025-12-01 12:00:00',
            'fixed_value' => null,
            'percent_value' => 50,
        ]);

        Offer::query()->create([
            'name' => 'حسم عيد الأطباء',
            'start' => '2024-12-01 12:00:00',
            'end' => '2025-01-01 12:00:00',
            'fixed_value' => 20,
            'percent_value' => null,
        ]);
    }
}
