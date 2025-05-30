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
        $patientsData = [
            [
                'firstName' => 'محمد',
                'lastName' => 'بكري',
                'phone' => '091111111',
                'whatsapp' => '091111111',
                'fatherName' => 'عبد القادر',
                'motherName' => 'بهيجة',
                'nationalNumber' => '123332432423',
                'address' => 'الفرقان,مفرق السكن',
                'gender' => 'male',
                'birth' => '2004-01-01',
                'notes' => 'وصف موجز',
            ],
            [
                'firstName' => 'حسن',
                'lastName' => 'فاضل',
                'phone' => '091111112',
                'fatherName' => 'ياسر',
                'motherName' => 'تغريد',
                'nationalNumber' => '34324232432',
                'address' => 'موكامبو,دوار المايل',
                'gender' => 'male',
                'birth' => '2003-01-01',
                'notes' => 'يعاني من حساسية موسمية',
            ],
            [
                'firstName' => 'محمود',
                'lastName' => 'حمدي',
                'phone' => '091111113',
                'fatherName' => 'معروف',
                'motherName' => 'محمد',
                'nationalNumber' => '34324232433',
                'address' => 'الزهراء,شارع النصر',
                'gender' => 'male',
                'birth' => '2002-05-12',
                'notes' => 'مريض سكري',
            ],
            [
                'firstName' => 'سارة',
                'lastName' => 'خالد',
                'phone' => '091111114',
                'fatherName' => 'خالد',
                'motherName' => 'منى',
                'nationalNumber' => '34324232434',
                'address' => 'الجميلية,قرب الحديقة',
                'gender' => 'female',
                'birth' => '2001-09-23',
                'notes' => 'تعاني من ضغط دم مرتفع',
            ],
            [
                'firstName' => 'ليلى',
                'lastName' => 'علي',
                'phone' => '091111115',
                'fatherName' => 'علي',
                'motherName' => 'سعاد',
                'nationalNumber' => '34324232435',
                'address' => 'السبيل,جانب المسجد',
                'gender' => 'female',
                'birth' => '2000-11-30',
                'notes' => 'حساسية من البنسلين',
            ],
            [
                'firstName' => 'أحمد',
                'lastName' => 'سالم',
                'phone' => '091111116',
                'fatherName' => 'سالم',
                'motherName' => 'هدى',
                'nationalNumber' => '34324232436',
                'address' => 'الميدان,شارع المدارس',
                'gender' => 'male',
                'birth' => '1999-03-15',
                'notes' => 'تعرض لحادث سير سابقاً',
            ],
            [
                'firstName' => 'نور',
                'lastName' => 'يوسف',
                'phone' => '091111117',
                'fatherName' => 'يوسف',
                'motherName' => 'أمينة',
                'nationalNumber' => '34324232437',
                'address' => 'الشيخ مقصود,قرب السوق',
                'gender' => 'female',
                'birth' => '1998-07-19',
                'notes' => 'تعاني من الربو',
            ],
            [
                'firstName' => 'رامي',
                'lastName' => 'جمال',
                'phone' => '091111118',
                'fatherName' => 'جمال',
                'motherName' => 'سمر',
                'nationalNumber' => '34324232438',
                'address' => 'السكري,شارع المستشفى',
                'gender' => 'male',
                'birth' => '1997-12-01',
                'notes' => 'أجرى عملية زائدة دودية',
            ],
            [
                'firstName' => 'دينا',
                'lastName' => 'سعيد',
                'phone' => '091111119',
                'fatherName' => 'سعيد',
                'motherName' => 'فاطمة',
                'nationalNumber' => '34324232439',
                'address' => 'الأنصاري,قرب المدرسة',
                'gender' => 'female',
                'birth' => '1996-04-10',
                'notes' => 'تعاني من مشاكل في النظر',
            ],
            [
                'firstName' => 'خالد',
                'lastName' => 'مروان',
                'phone' => '091111120',
                'fatherName' => 'مروان',
                'motherName' => 'سناء',
                'nationalNumber' => '34324232440',
                'address' => 'الحمدانية,شارع الجامعة',
                'gender' => 'male',
                'birth' => '1995-08-25',
                'notes' => 'لا يعاني من أمراض مزمنة',
            ],
        ];

        foreach ($patientsData as $data) {
            $data['clinic_id'] = Clinic::query()->inRandomOrder()->first()->id;
            $patient = Patient::query()->create($data);
        }
    }
}
