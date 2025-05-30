<?php

namespace Database\Seeders;

use App\Models\ChronicDiseas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChronicDiseasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $diseases = [
            [
                'id' => Str::uuid(),
                'patient_id' => DB::table('patients')->first()->id,
                'description' => 'السكري من النوع الثاني - تم التشخيص عام 2018، يتم التحكم به حالياً بواسطة الميتفورمين ونظام غذائي خاص.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'patient_id' => DB::table('patients')->first()->id,
                'description' => 'ارتفاع ضغط الدم - يتناول دواء أملوديبين 5 ملغ يومياً، الضغط تحت السيطرة حالياً.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'patient_id' => DB::table('patients')->skip(1)->first()->id,
                'description' => 'الربو الشعبي - يستخدم بخاخ السالبوتامول عند الحاجة، الأعراض تحت السيطرة.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'patient_id' => DB::table('patients')->skip(1)->first()->id,
                'description' => 'قصور الغدة الدرقية - يتناول ليفوثيروكسين 50 ميكروجرام يومياً، يحتاج لمتابعة دورية.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'patient_id' => DB::table('patients')->skip(2)->first()->id,
                'description' => 'التهاب المفاصل الروماتويدي - يعاني من آلام متوسطة في المفاصل الصغيرة، يتناول مضادات الالتهاب.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'patient_id' => DB::table('patients')->skip(2)->first()->id,
                'description' => 'أمراض القلب التاجية - خضع لعملية قسطرة سابقة، يتناول الأسبرين والأتورفاستاتين.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'patient_id' => DB::table('patients')->skip(3)->first()->id,
                'description' => 'الانسداد الرئوي المزمن - مدخن سابق، يستخدم جهاز استنشاق الكورتيكوستيرويد يومياً.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        ChronicDiseas::query()->insert($diseases);
    }
}