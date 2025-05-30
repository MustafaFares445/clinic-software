<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Laboratory;
use Illuminate\Support\Str;
use App\Models\FillingMaterial;
use Illuminate\Database\Seeder;

class FillingMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if required relations exist
        if (Laboratory::count() === 0 || Clinic::count() === 0) {
            $this->command->error('يجب وجود معامل وعيادات أولاً!');
            return;
        }

        $materials = [
            [
                'name' => 'حشوة أملغم',
                'color' => '#6B7280',
                'description' => 'حشوة فضية اللون تحتوي على الزئبق'
            ],
            [
                'name' => 'حشوة كومبوزيت',
                'color' => '#FFFFFF',
                'description' => 'حشوة بيضاء تتطابق مع لون السن'
            ],
            [
                'name' => 'حشوة زجاجية أيونومر',
                'color' => '#A7F3D0',
                'description' => 'نوع من الحشوات البيضاء التي تطلق الفلورايد'
            ],
            [
                'name' => 'حشوة راتنج معدل',
                'color' => '#FEF08A',
                'description' => 'حشوة متينة تشبه لون السن'
            ],
            [
                'name' => 'حشوة خزفية',
                'color' => '#F5F5F4',
                'description' => 'حشوة خزفية عالية الجودة'
            ],
            [
                'name' => 'حشوة ذهبية',
                'color' => '#FACC15',
                'description' => 'حشوة مصنوعة من سبائك الذهب'
            ],
            [
                'name' => 'حشوة سيراميك',
                'color' => '#ECFDF5',
                'description' => 'حشوة خزفية متطورة'
            ],
            [
                'name' => 'حشوة مؤقتة',
                'color' => '#FECACA',
                'description' => 'حشوة تستخدم لفترات قصيرة'
            ],
            [
                'name' => 'حشوة أكريليك',
                'color' => '#BFDBFE',
                'description' => 'حشوة بلاستيكية صلبة'
            ],
            [
                'name' => 'حشوة معدنية',
                'color' => '#D1D5DB',
                'description' => 'حشوة مصنوعة من المعادن'
            ],
        ];

        $clinics = Clinic::pluck('id')->toArray();
        $laboratories = Laboratory::pluck('id')->toArray();

        foreach ($materials as $material) {
            FillingMaterial::create([
                'id' => Str::uuid(),
                'name' => $material['name'],
                'color' => $material['color'],
                'laboratory_id' => $laboratories[array_rand($laboratories)],
                'clinic_id' => $clinics[array_rand($clinics)],
                'description' => $material['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('تم إنشاء ' . count($materials) . ' نوع من مواد الحشو بنجاح.');
    }
}