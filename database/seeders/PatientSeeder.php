<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Ill;
use App\Models\Medicine;
use App\Models\Patient;
use Illuminate\Database\Seeder;

final class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patient1 = Patient::query()->create([
            'firstName' => 'محمد',
            'lastName' => 'بكري',
            'phone' => '091111111',
            'age' => 21,
            'fatherName' => 'عبد القادر',
            'motherName' => 'بهيجة',
            'nationalNumber' => '123332432423',
            'address' => 'الفرقان,مفرق السكن',
            'gender' => 'male',
            'birth' => '2003',
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'notes' => 'وصف موجز',
        ]);

        $patient1->permanentIlls()->sync([
            Ill::query()->inRandomOrder()->first()->id => ['notes' => 'مريض يعاني من هذا المرض بشكل مزمن']
        ]);
        $patient1->permanentMedicines()->sync([
            Medicine::query()->inRandomOrder()->first()->id => ['notes' => 'يأخذ هذا الدواء بشكل يومي']
        ]);

        $patient2 = Patient::query()->create([
            'firstName' => 'حسن',
            'lastName' => 'فاضل',
            'phone' => '091111112',
            'age' => 20,
            'gender' => 'male',
            'birth' => '2003',
            'fatherName' => 'ياسر',
            'motherName' => 'تغريد',
            'nationalNumber' => '34324232432',
            'address' => 'موكامبو,دوار المايل',
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'notes' => 'وصف موجز',
        ]);

        $patient2->permanentIlls()->sync([
            Ill::query()->inRandomOrder()->first()->id => ['notes' => 'تم تشخيص المرض منذ 3 سنوات']
        ]);
        $patient2->permanentMedicines()->sync([
            Medicine::query()->inRandomOrder()->first()->id => ['notes' => 'يأخذ الدواء مرتين يوميا بعد الأكل']
        ]);
    }
}
