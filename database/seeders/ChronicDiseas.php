<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChronicDiseasesSeeder extends Seeder
{
    /**
     * تشغيل بذور البيانات للأمراض المزمنة
     *
     * @return void
     */
    public function run()
    {
        DB::table('chronic_diseases')->truncate();

        $patients = Patient::all();

        $diseasesDescriptions = [
            'مريض يعاني من مرض السكري النوع الثاني منذ 5 سنوات، يتناول الأنسولين بانتظام',
            'حالة ضغط دم مرتفع، يتناول أدوية ضغط الدم يومياً',
            'ربو شعبي مزمن، يستخدم بخاخات الكورتيزون بانتظام',
            'قصور في الغدة الدرقية، يتناول الثيروكسين يومياً',
            'تهاب مفاصل روماتويدي، يعاني من آلام متكررة في المفاصل',
            'حساسية صدرية مزمنة مع حساسية من الغبار وحبوب اللقاح',
            'فشل كلوي مزمن، يحتاج إلى غسيل كلى ثلاث مرات أسبوعياً',
            'صرع مزمن، يتناول أدوية مضادة للتشنجات يومياً',
            'انسداد رئوي مزمن نتيجة التدخين لسنوات طويلة',
            'كولسترول مرتفع في الدم مع تاريخ عائلي لأمراض القلب'
        ];

        $chronicDiseases = [];

        foreach ($diseasesDescriptions as $description) {
            $randomPatient = $patients->random();

            $chronicDiseases[] = [
                'id' => Str::uuid(),
                'patient_id' => $randomPatient->id,
                'description' => $description,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('chronic_diseases')->insert($chronicDiseases);
    }
}