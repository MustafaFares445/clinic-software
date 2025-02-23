<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\Plan;
use Illuminate\Database\Seeder;

final class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Coupon::query()->create([
            'fixed_value' => 10,
            'percent_value' => null,
            'expire_at' => '2025-12-12 12:00:00',
            'plan_id' => Plan::query()->inRandomOrder()->first()->id,
            'used_number' => 1,
            'is_active' => true,
        ]);

        Coupon::query()->create([
            'fixed_value' => null,
            'percent_value' => 20,
            'expire_at' => '2025-12-12 12:00:00',
            'plan_id' => Plan::query()->inRandomOrder()->first()->id,
            'used_number' => 2,
            'is_active' => true,
        ]);
    }
}
