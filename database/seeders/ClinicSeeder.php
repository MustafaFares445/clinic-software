<?php

namespace Database\Seeders;

use App\Enums\ClinicTypes;
use App\Models\Clinic;
use App\Models\Plan;
use App\Models\Specification;
use Illuminate\Database\Seeder;

final class ClinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clinic1 = Clinic::query()->create([
            'name' => 'عيادة مصطفى السنية',
            'address' => 'المحافظة ,خلف فرنسيسكان',
            'longitude' => 36.3015,
            'latitude' => 33.5102,
            'description' => 'عيادة أسنان متخصصة في طب الأسنان التجميلي',
            'is_banned' => false,
            'type' => ClinicTypes::CLINIC,
        ]);
        $clinic1->plans()->sync([Plan::query()->inRandomOrder()->first()->id]);
        $clinic1->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);
        $clinic1->workingDays()->createMany([
            ['day' => 'sun', 'start' => '08:00', 'end' => '14:00'],
            ['day' => 'mon', 'start' => '08:00', 'end' => '14:00'],
            ['day' => 'tue', 'start' => '08:00', 'end' => '14:00'],
            ['day' => 'wed', 'start' => '08:00', 'end' => '14:00'],
            ['day' => 'thu', 'start' => '08:00', 'end' => '14:00'],
        ]);

        // ********************************//
        // $clinic2 = Clinic::query()->create([
        //     'name' => 'الإحسان',
        //     'address' => 'الميدان ,خلف الثانوية السورية',
        //     'longitude' => 36.3067,
        //     'latitude' => 33.5134,
        //     'description' => 'مركز صحي يقدم خدمات صحية شاملة',
        //     'is_banned' => false,
        //     'type' => ClinicTypes::HEALTH_CENTER,
        // ]);
        // $clinic2->plans()->sync([Plan::query()->inRandomOrder()->first()->id]);
        // $clinic2->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);
        // $clinic2->workingDays()->createMany([
        //     ['day' => 'sat', 'start' => '09:00', 'end' => '17:00'],
        //     ['day' => 'sun', 'start' => '09:00', 'end' => '17:00'],
        //     ['day' => 'mon', 'start' => '09:00', 'end' => '17:00'],
        //     ['day' => 'tue', 'start' => '09:00', 'end' => '17:00'],
        //     ['day' => 'wed', 'start' => '09:00', 'end' => '17:00'],
        // ]);

        // // ********************************//
        // $clinic2 = Clinic::query()->create([
        //     'name' => 'مشفى الرجاء',
        //     'address' => 'الميدان ,خلف الثانوية السورية',
        //     'longitude' => 36.3089,
        //     'latitude' => 33.5156,
        //     'description' => 'مستشفى عام يقدم خدمات طبية متقدمة',
        //     'is_banned' => false,
        //     'type' => ClinicTypes::HOSPITAL,
        // ]);
        // $clinic2->plans()->sync([Plan::query()->inRandomOrder()->first()->id]);
        // $clinic2->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);
        // $clinic2->workingDays()->createMany([
        //     ['day' => 'sun', 'start' => '00:00', 'end' => '23:59'],
        //     ['day' => 'mon', 'start' => '00:00', 'end' => '23:59'],
        //     ['day' => 'tue', 'start' => '00:00', 'end' => '23:59'],
        //     ['day' => 'wed', 'start' => '00:00', 'end' => '23:59'],
        //     ['day' => 'thu', 'start' => '00:00', 'end' => '23:59'],
        //     ['day' => 'fri', 'start' => '00:00', 'end' => '23:59'],
        //     ['day' => 'sat', 'start' => '00:00', 'end' => '23:59'],
        // ]);

        // // ********************************//
        // $clinic3 = Clinic::query()->create([
        //     'name' => 'عيادة الأمل',
        //     'address' => 'شارع النصر، بجانب البنك الوطني',
        //     'longitude' => null,
        //     'latitude' => null,
        //     'description' => 'عيادة متخصصة في طب الأسرة',
        //     'is_banned' => false,
        //     'type' => ClinicTypes::CLINIC,
        // ]);
        // $clinic3->plans()->sync([Plan::query()->inRandomOrder()->first()->id]);
        // $clinic3->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);

        // // ********************************//
        // $clinic4 = Clinic::query()->create([
        //     'name' => 'مركز الشفاء الصحي',
        //     'address' => 'حي السلام، مقابل المدرسة الثانوية',
        //     'longitude' => 36.3022,
        //     'latitude' => 33.5111,
        //     'description' => 'مركز صحي شامل يقدم خدمات متعددة',
        //     'is_banned' => false,
        //     'type' => ClinicTypes::HEALTH_CENTER->value,
        // ]);
        // $clinic4->plans()->sync([Plan::query()->inRandomOrder()->first()->id]);
        // $clinic4->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);
        // $clinic4->workingDays()->createMany([
        //     ['day' => 'sun', 'start' => '08:00', 'end' => '16:00'],
        //     ['day' => 'mon', 'start' => '08:00', 'end' => '16:00'],
        //     ['day' => 'tue', 'start' => '08:00', 'end' => '16:00'],
        //     ['day' => 'wed', 'start' => '08:00', 'end' => '16:00'],
        //     ['day' => 'thu', 'start' => '08:00', 'end' => '16:00'],
        // ]);

        // // ********************************//
        // $clinic5 = Clinic::query()->create([
        //     'name' => 'مستشفى المدينة الطبي',
        //     'address' => 'طريق المطار، المنطقة الصناعية',
        //     'longitude' => null,
        //     'latitude' => null,
        //     'description' => 'مستشفى عام يقدم خدمات طبية متقدمة',
        //     'is_banned' => false,
        //     'type' => ClinicTypes::HOSPITAL->value,
        // ]);
        // $clinic5->plans()->sync([Plan::query()->inRandomOrder()->first()->id]);
        // $clinic5->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);
        // $clinic5->workingDays()->createMany([
        //     ['day' => 'sun', 'start' => '00:00', 'end' => '23:59'],
        //     ['day' => 'mon', 'start' => '00:00', 'end' => '23:59'],
        //     ['day' => 'tue', 'start' => '00:00', 'end' => '23:59'],
        //     ['day' => 'wed', 'start' => '00:00', 'end' => '23:59'],
        //     ['day' => 'thu', 'start' => '00:00', 'end' => '23:59'],
        //     ['day' => 'fri', 'start' => '00:00', 'end' => '23:59'],
        //     ['day' => 'sat', 'start' => '00:00', 'end' => '23:59'],
        // ]);
    }
}
