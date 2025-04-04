<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SpecificationSeeder::class,
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
            BillingTransactionSeeder::class
        ]);
    }
}
