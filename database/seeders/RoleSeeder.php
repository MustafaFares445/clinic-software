<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

final class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::query()->firstOrCreate([
            'name' => 'super admin',
        ]);

        Role::query()->firstOrCreate([
            'name' => 'admin',
        ]);

        Role::query()->firstOrCreate([
            'name' => 'doctor',
        ]);

        Role::query()->firstOrCreate([
            'name' => 'secreter',
        ]);
    }
}
