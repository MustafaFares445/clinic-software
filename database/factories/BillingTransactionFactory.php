<?php

namespace Database\Factories;

use App\Models\BillingTransaction;
use App\Models\Clinic;
use App\Models\Reservation;
use App\Models\MedicalTransaction;
use App\Models\MedicalTransactions;
use App\Models\Medicine;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillingTransactionFactory extends Factory
{
    protected $model = BillingTransaction::class;

    public function definition(): array
    {
        return [
            'clinic_id' => Clinic::factory(),
            'type' => $this->faker->randomElement(['in' , 'out']),
            'amount' => $this->faker->randomFloat(2, 1, 1000),
            'description' => $this->faker->sentence(),
            'user_id' => User::factory(),
        ];
    }

    public function forReservation(): self
    {
        return $this->state([
            'model_type' => Reservation::class,
            'model_id' => Reservation::factory()->create()->id,
        ]);
    }

    public function forMedicalTransaction(): self
    {
        return $this->state([
            'model_type' => MedicalTransactions::class,
            'model_id' => MedicalTransactions::factory()->create()->id,
        ]);
    }
}