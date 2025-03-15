<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->firstOrCreate([
            'firstName' => 'super admin',
            'lastName' => 'user',
            'email' => 'super.admin@gmail.com',
            'username' => 'super-admin',
            // 'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'remember_token' => Str::random(10),
            'clinic_id' => Clinic::query()->first()->id,
        ])->assignRole('super admin');

        User::query()->firstOrCreate([
            'firstName' => 'admin',
            'lastName' => 'user',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            // 'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'remember_token' => Str::random(10),
            'clinic_id' => Clinic::query()->first()->id,
        ])->assignRole('admin');

        User::query()->firstOrCreate([
            'firstName' => 'doctor',
            'lastName' => 'user',
            'email' => 'doctor@gmail.com',
            'username' => 'doctor',
            // 'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'remember_token' => Str::random(10),
            'clinic_id' => Clinic::query()->first()->id,
        ])->assignRole('doctor');

        User::query()->firstOrCreate([
            'firstName' => 'secreter',
            'lastName' => 'user',
            'email' => 'secreter@gmail.com',
            'username' => 'secreter',
            // 'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'remember_token' => Str::random(10),
            'clinic_id' => Clinic::query()->first()->id,
        ])->assignRole('secreter');

        User::query()->firstOrCreate([
            'firstName' => 'doctor admin',
            'lastName' => 'user',
            'email' => 'doctor.admin@gmail.com',
            'username' => 'doctor-admin',
            //            'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'remember_token' => Str::random(10),
            'clinic_id' => Clinic::query()->first()->id,
        ])->assignRole(['admin', 'doctor']);
    }
}
