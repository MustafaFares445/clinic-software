<?php

namespace Database\Seeders;

use App\Enums\RecordTypes;
use App\Enums\ReservationTypes;
use App\Models\Clinic;
use App\Models\Ill;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Record;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicinesCount = Medicine::query()->count();
        $illCount = Ill::query()->count();
       $record = Record::query()->create([
            'patient_id' => Patient::query()->inRandomOrder()->first()->id,
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'reservation_id' => Reservation::query()->inRandomOrder()->first()->id,
            'description' => 'وصف موجز',
            'type' => RecordTypes::APPOINTMENT,
            'price' => 5,
       ]);

       $record->medicines()->sync(Medicine::query()->inRandomOrder()->take(rand(1 , $medicinesCount))->pluck('id')->toArray());
       $record->ills()->sync(Ill::query()->inRandomOrder()->take(rand(1 , $illCount))->pluck('id')->toArray());
       $record->doctors()->sync(User::query()->inRandomOrder()->take(rand(1 , 2))->pluck('id')->toArray());
       $record->transaction()->create([
          'type' => 'income',
          'amount' => 5,
          'clinic_id' => Clinic::query()->inRandomOrder()->first()->id
       ]);

       $record2  = Record::query()->create([
            'patient_id' => Patient::query()->inRandomOrder()->first()->id,
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'reservation_id' => Reservation::query()->inRandomOrder()->first()->id,
            'description' => 'وصف موجز',
            'type' => RecordTypes::SURGERY,
            'price' => 50,
       ]);

        $record2->medicines()->sync(Medicine::query()->inRandomOrder()->take(rand(1 , $medicinesCount))->pluck('id')->toArray());
        $record2->ills()->sync(Ill::query()->inRandomOrder()->take(rand(1 , $illCount))->pluck('id')->toArray());
        $record2->doctors()->sync(User::query()->inRandomOrder()->take(rand(1 , 2))->pluck('id')->toArray());

        $record2->transaction()->create([
            'type' => 'income',
            'amount' => 50,
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id
        ]);

       $record3 =  Record::query()->create([
            'patient_id' => Patient::query()->inRandomOrder()->first()->id,
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id,
            'reservation_id' => Reservation::query()->inRandomOrder()->first()->id,
            'description' => 'وصف موجز',
            'type' => RecordTypes::INSPECTION,
            'price' => 10,
       ]);

        $record3->medicines()->sync(Medicine::query()->inRandomOrder()->take(rand(1 , $medicinesCount))->pluck('id')->toArray());
        $record3->ills()->sync(Ill::query()->inRandomOrder()->take(rand(1 , $illCount))->pluck('id')->toArray());
        $record3->doctors()->sync(User::query()->inRandomOrder()->take(rand(1 , 2))->pluck('id')->toArray());

        $record3->transaction()->create([
            'type' => 'income',
            'amount' => 10,
            'clinic_id' => Clinic::query()->inRandomOrder()->first()->id
        ]);
    }
}
