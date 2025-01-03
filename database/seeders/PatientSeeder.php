<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Patient::query()->create([
            'firstName' => 'محمد',
            'lastName' => 'بكري',
            'phone' => '091111111',
            'age' => 21,
            'fatherName' => 'عبد القادر',
            'motherName' => 'بهيجة',
            'nationalNumber' =>  '123332432423',
            'address' => 'الفرقان,مفرق السكن',
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'description' => 'وصف موجز'
        ]);

        Patient::query()->create([
            'firstName' => 'حسن',
            'lastName' => 'فاضل',
            'phone' => '091111112',
            'age' => 20,
            'fatherName' => 'ياسر',
            'motherName' => 'تغريد',
            'nationalNumber' =>  '34324232432',
            'address' => 'موكامبو,دوار المايل',
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'description' => 'وصف موجز' ,
        ]);
    }
}
