<?php

namespace Database\Seeders;

use App\Models\Specification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Specification::query()->create([
           'name' => 'سنية',
        ]);

        Specification::query()->create([
            'name' => 'جراحة سنية'
        ]);

        Specification::query()->create([
            'name' => 'تقويم'
        ]);
    }
}
