<?php

namespace Database\Seeders;

use App\Models\Ill;
use Illuminate\Database\Seeder;

class IllSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ill::query()->create([
            'name' => 'نخر بسيط',
            'description' => null
        ])->categories()->sync([1]);

        Ill::query()->create([
            'name' => 'ضرس عقل',
            'description' => null
        ])->categories()->sync([1]);

        Ill::query()->create([
            'name' => 'التهاب لسة',
            'description' => null
        ])->categories()->sync([1]);

        Ill::query()->create([
            'name' => 'خراجات',
            'description' => null
        ])->categories()->sync([2]);

        Ill::query()->create([
            'name' => 'نخر عصب',
            'description' => null
        ])->categories()->sync([2]);
    }
}
