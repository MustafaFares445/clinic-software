<?php

namespace Database\Factories;

use App\Enums\RecordMedicinesTypes;
use App\Models\Medicine;
use App\Models\Record;
use Illuminate\Database\Eloquent\Factories\Factory;

final class MedicineRecordFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => fake()->uuid(),
            'medicine_id' => Medicine::factory(),
            'record_id' => Record::factory(),
            'type' => fake()->randomElement(RecordMedicinesTypes::cases())->value,
            'note' => fake()->optional()->paragraph(),
        ];
    }
}
