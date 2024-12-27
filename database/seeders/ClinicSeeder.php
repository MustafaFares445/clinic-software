<?php

namespace Database\Seeders;

use App\Enums\ClinicTypes;
use App\Models\Clinic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clinic1 = Clinic::query()->create([
            'name' => 'عيادة مصطفى السنية',
            'address' => 'المحافظة ,خلف فرنسيسكان',
            'longitude' => null,
            'latitude' => null,
            'description' => null,
            'is_banned' => false ,
            'type' => ClinicTypes::CLINIC
        ]);
        $clinic1->plans()->sync([1]);
        $clinic1->categories()->sync([1]);

        //********************************//
        $clinic2 =  Clinic::query()->create([
            'name' => 'الإحسان',
            'address' => 'الميدان ,خلف الثانوية السورية',
            'longitude' => null,
            'latitude' => null,
            'description' => null,
            'is_banned' => false ,
            'type' => ClinicTypes::HEALTH_CENTER
        ]);
        $clinic2->plans()->sync([2]);
        $clinic2->categories()->sync([2]);

        //********************************//
        $clinic2 =  Clinic::query()->create([
            'name' => 'مشفى الرجاء',
            'address' => 'الميدان ,خلف الثانوية السورية',
            'longitude' => null,
            'latitude' => null,
            'description' => null,
            'is_banned' => false ,
            'type' => ClinicTypes::HOSPITAL
        ]);
        $clinic2->plans()->sync([3]);
        $clinic2->categories()->sync([1]);
    }
}
