<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LaboratorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clinicIds = DB::table('clinics')->pluck('id')->toArray();

        if (empty($clinicIds)) {
            $this->command->error('لم يتم العثور على عيادات. يرجى إنشاء العيادات أولاً.');
            return;
        }

        $laboratories = [
            [
                'name' => 'المختبر المرجعي الوطني',
                'address' => '123 شارع الطبي، مدينة الصحة',
                'phone' => '+966123456789',
                'whatsapp' => '+966123456789',
            ],
            [
                'name' => 'مركز التشخيص المتقدم',
                'address' => '456 حديقة العلوم، مدينة الطب الحيوي',
                'phone' => '+966598765432',
                'whatsapp' => '+966598765432',
            ],
            [
                'name' => 'مختبرات الباثولوجيا الدقيقة',
                'address' => '789 جادة البحث، منطقة التكنولوجيا',
                'phone' => '+966511223344',
                'whatsapp' => null,
            ],
            [
                'name' => 'المختبر السريري المدينة',
                'address' => '321 شارع المستشفى، وسط المدينة',
                'phone' => '+966556789012',
                'whatsapp' => '+966556789012',
            ],
            [
                'name' => 'مختبرات الفحص السريع',
                'address' => '654 طريق سريع، الضاحية',
                'phone' => '+966534567890',
                'whatsapp' => null,
            ],
        ];

        foreach ($laboratories as $lab) {
            DB::table('laboratories')->insert([
                'id' => Str::uuid(),
                'name' => $lab['name'],
                'clinic_id' => $clinicIds[array_rand($clinicIds)],
                'address' => $lab['address'],
                'phone' => $lab['phone'],
                'whatsapp' => $lab['whatsapp'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}