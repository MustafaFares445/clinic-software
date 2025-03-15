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
        $record = Record::query()->create([
            'patient_id' => Patient::query()->inRandomOrder()->first()->id,
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'reservation_id' => Reservation::query()->inRandomOrder()->first()->id,
            'description' => 'وصف موجز',
            'type' => RecordTypes::APPOINTMENT,
            'dateTime' => now(),
        ]);

        $record->ills()->attach(Ill::query()->inRandomOrder()->first()->id, [
            'type' => RecordIllsTypes::DIAGNOSED,
        ]);

        $record->ills()->attach(Ill::query()->inRandomOrder()->first()->id, [
            'type' => RecordIllsTypes::TRANSIENT,
        ]);

        $record->medicines()->attach(Medicine::query()->inRandomOrder()->first()->id, [
            'type' => RecordMedicinesTypes::DIAGNOSED,
            'note' => '200 gm من الدواء ثلاث مرات باليوم لمدة أسبوع',
        ]);

        $record->medicines()->attach(Medicine::query()->inRandomOrder()->first()->id, [
            'type' => RecordMedicinesTypes::TRANSIENT,
            'note' => '400 gm من الدواء 5 مرات باليوم لمدة شهر',
        ]);

        $record->doctors()->sync(User::query()->inRandomOrder()->take(rand(1, 2))->pluck('id')->toArray());

        $record->transactions()->create([
            'medicine_id' => Medicine::query()->inRandomOrder()->first()->id,
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'type' => 'out',
            'quantity' => 5,
            'description' => null,
            'doctor_id' => User::query()->inRandomOrder()->first()->id,
        ]);

        $record2 = Record::query()->create([
            'patient_id' => Patient::query()->inRandomOrder()->first()->id,
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'reservation_id' => Reservation::query()->inRandomOrder()->first()->id,
            'description' => 'وصف موجز',
            'type' => RecordTypes::SURGERY,
            'dateTime' => now(),
        ]);

        $record2->ills()->attach(Ill::query()->inRandomOrder()->first()->id, [
            'type' => RecordIllsTypes::DIAGNOSED,
        ]);

        $record2->ills()->attach(Ill::query()->inRandomOrder()->first()->id, [
            'type' => RecordIllsTypes::TRANSIENT,
        ]);

        $record2->doctors()->sync(User::query()->inRandomOrder()->take(rand(1, 2))->pluck('id')->toArray());

        $record2->transactions()->create([
            'medicine_id' => Medicine::query()->inRandomOrder()->first()->id,
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'type' => 'out',
            'quantity' => 5,
            'description' => null,
            'doctor_id' => User::query()->inRandomOrder()->first()->id,
        ]);

        $record3 = Record::query()->create([
            'patient_id' => Patient::query()->inRandomOrder()->first()->id,
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'reservation_id' => Reservation::query()->inRandomOrder()->first()->id,
            'description' => 'وصف موجز',
            'type' => RecordTypes::INSPECTION,
            'dateTime' => now(),
        ]);

        $record3->medicines()->attach(Medicine::query()->inRandomOrder()->first()->id, [
            'type' => RecordMedicinesTypes::DIAGNOSED,
        ]);

        $record3->medicines()->attach(Medicine::query()->inRandomOrder()->first()->id, [
            'type' => RecordMedicinesTypes::TRANSIENT,
            'note' => '400 gm من الدواء 5 مرات باليوم لمدة شهر',
        ]);

        $record3->doctors()->sync(User::query()->inRandomOrder()->take(rand(1, 2))->pluck('id')->toArray());

        $record3->transactions()->create([
            'medicine_id' => Medicine::query()->inRandomOrder()->first()->id,
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'type' => 'out',
            'quantity' => 5,
            'description' => null,
            'doctor_id' => User::query()->inRandomOrder()->first()->id,
        ]);
    }
}
