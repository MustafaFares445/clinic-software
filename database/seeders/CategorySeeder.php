<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::query()->create([
           'name' => 'سنية',
        ]);

        Category::query()->create([
            'name' => 'جراحة سنية'
        ]);

        Category::query()->create([
            'name' => 'تقويم'
        ]);
    }
}
