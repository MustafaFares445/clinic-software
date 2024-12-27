<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
           CategorySeeder::class,
           RoleSeeder::class,
           PlanSeeder::class,
           ClinicSeeder::class,
           UserSeeder::class,
           IllSeeder::class,
           MedicineSeeder::class,
           PatientSeeder::class,
           ReservationSeeder::class,
           RecordSeeder::class,
           OfferSeeder::class,
        ]);
    }
}
