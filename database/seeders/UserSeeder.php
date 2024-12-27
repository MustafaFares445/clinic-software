<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->firstOrCreate([
            'first_name' => 'super admin',
            'email' => 'super.admin@gmail.com',
            'username' => 'super-admin',
           // 'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'remember_token' => Str::random(10),
            'clinic_id' => 1
        ])->assignRole('super admin');

        User::query()->firstOrCreate([
            'first_name' => 'admin',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            //'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'remember_token' => Str::random(10),
            'clinic_id' => 1
        ])->assignRole('admin');

        User::query()->firstOrCreate([
            'first_name' => 'doctor',
            'email' => 'doctor@gmail.com',
            'username' => 'doctor',
            //'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'remember_token' => Str::random(10),
            'clinic_id' => 1
        ])->assignRole('doctor');

        User::query()->firstOrCreate([
            'first_name' => 'secreter',
            'email' => 'secreter@gmail.com',
            'username' => 'secreter',
           // 'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'remember_token' => Str::random(10),
            'clinic_id' => 1
        ])->assignRole('secreter');
//
        User::query()->firstOrCreate([
            'first_name' => 'doctor',
            'last_name' => 'admin',
            'email' => 'doctor.admin@gmail.com',
            'username' => 'doctor-admin',
//            'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'remember_token' => Str::random(10),
            'clinic_id' => 1
        ])->assignRole(['admin' , 'doctor']);
    }
}
