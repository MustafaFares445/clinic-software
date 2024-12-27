<?php

namespace Database\Seeders;

use App\Enums\RecordTypes;
use App\Enums\ReservationTypes;
use App\Models\Record;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $record = Record::query()->create([
            'patient_id' => 1,
            'clinic_id' => 1,
            'reservation_id' => 1,
            'description' => 'وصف موجز',
            'type' => RecordTypes::APPOINTMENT,
            'price' => 5,
       ]);

       $record->medicines()->sync([1 , 2]);
       $record->ills()->sync([1 , 2 , 3]);
       $record->doctors()->sync([3]);
       $record->transaction()->create([
          'type' => 'income',
          'amount' => 5,
          'clinic_id' => 1
       ]);

       $record2  = Record::query()->create([
            'patient_id' => 2,
            'clinic_id' => 1,
            'reservation_id' => 2,
            'description' => 'وصف موجز',
            'type' => RecordTypes::SURGERY,
            'price' => 50,
       ]);

        $record2->medicines()->sync([2 , 3]);
        $record2->ills()->sync([3 , 4]);
        $record2->doctors()->sync([3]);

        $record2->transaction()->create([
            'type' => 'income',
            'amount' => 50,
            'clinic_id' => 1
        ]);

       $record3 =  Record::query()->create([
            'patient_id' => 1,
            'clinic_id' => 1,
            'reservation_id' => 3,
            'description' => 'وصف موجز',
            'type' => RecordTypes::INSPECTION,
            'price' => 10,
       ]);

        $record3->medicines()->sync([4 , 5]);
        $record3->ills()->sync([2 , 3 , 4]);
        $record3->doctors()->sync([3]);

        $record3->transaction()->create([
            'type' => 'income',
            'amount' => 10,
            'clinic_id' => 1
        ]);
    }
}
