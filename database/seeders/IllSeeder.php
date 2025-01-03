<?php

namespace Database\Seeders;

use App\Models\Ill;
use App\Models\Specification;
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
        ])->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);

        Ill::query()->create([
            'name' => 'ضرس عقل',
            'description' => null
        ])->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);

        Ill::query()->create([
            'name' => 'التهاب لسة',
            'description' => null
        ])->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);

        Ill::query()->create([
            'name' => 'خراجات',
            'description' => null
        ])->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);

        Ill::query()->create([
            'name' => 'نخر عصب',
            'description' => null
        ])->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);
    }
}
