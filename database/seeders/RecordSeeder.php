<?php

namespace Database\Seeders;

use App\Enums\RecordIllsTypes;
use App\Enums\RecordMedicinesTypes;
use App\Enums\RecordTypes;
use App\Models\Clinic;
use App\Models\Ill;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Record;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Seeder;

final class RecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recordsData = [
            [
                'type' => RecordTypes::APPOINTMENT,
                'notes' => 'ملاحظة موجزة',
                'ills' => true,
                'medicines' => true,
            ],
            [
                'type' => RecordTypes::SURGERY,
                'notes' => 'ملاحظة موجزة',
                'ills' => true,
                'medicines' => false,
            ],
            [
                'type' => RecordTypes::INSPECTION,
                'notes' => 'ملاحظة',
                'ills' => false,
                'medicines' => true,
            ],
            [
                'type' => RecordTypes::APPOINTMENT,
                'notes' => 'زيارة متابعة للحالة',
                'ills' => false,
                'medicines' => false,
            ],
            [
                'type' => RecordTypes::SURGERY,
                'notes' => 'عملية جراحية ناجحة',
                'ills' => true,
                'medicines' => true,
            ],
            [
                'type' => RecordTypes::INSPECTION,
                'notes' => 'فحص روتيني',
                'ills' => true,
                'medicines' => false,
            ],
            [
                'type' => RecordTypes::APPOINTMENT,
                'notes' => 'استشارة طبية',
                'ills' => true,
                'medicines' => true,
            ],
            [
                'type' => RecordTypes::SURGERY,
                'notes' => 'عملية بسيطة',
                'ills' => false,
                'medicines' => true,
            ],
            [
                'type' => RecordTypes::INSPECTION,
                'notes' => 'مراجعة نتائج التحاليل',
                'ills' => false,
                'medicines' => false,
            ],
        ];

        foreach ($recordsData as $data) {
            $record = Record::query()->create([
                'patient_id' => Patient::query()->inRandomOrder()->first()->id,
                'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
                'reservation_id' => Reservation::query()->inRandomOrder()->first()->id,
                'notes' => $data['notes'],
                'type' => $data['type'],
                'dateTime' => now(),
            ]);

            if ($data['ills']) {
                $record->ills()->attach(Ill::query()->inRandomOrder()->first()->id, [
                    'type' => RecordIllsTypes::DIAGNOSED,
                ]);
                $record->ills()->attach(Ill::query()->inRandomOrder()->first()->id, [
                    'type' => RecordIllsTypes::TRANSIENT,
                ]);
            }

            if ($data['medicines']) {
                $record->medicines()->attach(Medicine::query()->inRandomOrder()->first()->id, [
                    'type' => RecordMedicinesTypes::DIAGNOSED,
                    'notes' => '200 gm من الدواء ثلاث مرات باليوم لمدة أسبوع',
                ]);
                $record->medicines()->attach(Medicine::query()->inRandomOrder()->first()->id, [
                    'type' => RecordMedicinesTypes::TRANSIENT,
                    'notes' => '400 gm من الدواء 5 مرات باليوم لمدة شهر',
                ]);
            }

            $record->doctors()->sync(
                User::query()->inRandomOrder()->take(rand(1, 2))->pluck('id')->toArray()
            );

            $record->transactions()->create([
                'medicine_id' => Medicine::query()->inRandomOrder()->first()->id,
                'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
                'type' => 'out',
                'quantity' => 5,
                'description' => null,
                'doctor_id' => User::query()->inRandomOrder()->first()->id,
            ]);
        }
    }
}
