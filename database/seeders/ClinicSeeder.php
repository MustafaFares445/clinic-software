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
            'longitude' => null,
            'latitude' => null,
            'description' => null,
            'is_banned' => false,
            'type' => ClinicTypes::CLINIC,
        ]);
        $clinic1->plans()->sync([Plan::query()->inRandomOrder()->first()->id]);
        $clinic1->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);

        // ********************************//
        $clinic2 = Clinic::query()->create([
            'name' => 'الإحسان',
            'address' => 'الميدان ,خلف الثانوية السورية',
            'longitude' => null,
            'latitude' => null,
            'description' => null,
            'is_banned' => false,
            'type' => ClinicTypes::HEALTH_CENTER,
        ]);
        $clinic2->plans()->sync([Plan::query()->inRandomOrder()->first()->id]);
        $clinic2->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);

        // ********************************//
        $clinic2 = Clinic::query()->create([
            'name' => 'مشفى الرجاء',
            'address' => 'الميدان ,خلف الثانوية السورية',
            'longitude' => null,
            'latitude' => null,
            'description' => null,
            'is_banned' => false,
            'type' => ClinicTypes::HOSPITAL,
        ]);
        $clinic2->plans()->sync([Plan::query()->inRandomOrder()->first()->id]);
        $clinic2->specifications()->sync([Specification::query()->inRandomOrder()->first()->id]);
    }
}
