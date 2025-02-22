<?php

namespace Database\Factories;

use App\Enums\RecordIllsTypes;
use App\Models\Ill;
use App\Models\Record;
use Illuminate\Database\Eloquent\Factories\Factory;

class IllRecordFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ill_id' => Ill::factory(),
            'record_id' => Record::factory(),
            'type' => fake()->randomElement(RecordIllsTypes::cases())->value,
        ];
    }

    /**
     * Configure the factory to create a diagnosed ill record.
     */
    public function diagnosed(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => RecordIllsTypes::DIAGNOSED->value,
        ]);
    }

    /**
     * Configure the factory to create a transient ill record.
     */
    public function transient(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => RecordIllsTypes::TRANSIENT->value,
        ]);
    }
} 