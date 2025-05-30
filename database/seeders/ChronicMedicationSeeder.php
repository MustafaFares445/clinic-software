<?php

namespace Database\Seeders;

use App\Models\ChronicMedication;
use App\Models\Patient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChronicMedicationSeeder extends Seeder
{
    /**
     * تشغيل بذور البيانات للأمراض المزمنة
     *
     * @return void
     */
    public function run()
    {
        DB::table('chronic_medications')->truncate();

        $patients = Patient::all();

        $chronicMdicationsDescriptions = [
            'يتناول الأنسولين بانتظام',
            'يتناول أدوية ضغط الدم يومياً',
            'يستخدم بخاخات الكورتيزون بانتظام',
            'يتناول الثيروكسين يومياً',
            'يعاني من آلام متكررة في المفاصل',
            'صرع مزمن، يتناول أدوية مضادة للتشنجات يومياً',
        ];

        $chronicMdications = [];

        foreach ($chronicMdicationsDescriptions as $description) {
            $randomPatient = $patients->random();

            $chronicMdications[] = [
                'id' => Str::uuid(),
                'patient_id' => $randomPatient->id,
                'description' => $description,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        ChronicMedication::query()->insert($chronicMdications);
    }
}