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
           TeethSeeder::class,
           SpecificationSeeder::class,
           PlanSeeder::class,
           ClinicSeeder::class,
           RoleSeeder::class,
           UserSeeder::class,
           PatientSeeder::class,
           LaboratorySeeder::class,
           TreatmentSeeder::class,
           MedicalSessionSeeder::class,
           FillingMaterialSeeder::class,
           ChronicDiseasesSeeder::class,
           ChronicMedicationSeeder::class,
           MedicalCaseSeeder::class,
           RecordSeeder::class,
           DoctorRecordSeeder::class,
           BillingSeeder::class,
           ReservationSeeder::class
        ]);
    }
}
