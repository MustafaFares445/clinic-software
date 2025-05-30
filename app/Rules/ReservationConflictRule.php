<?php

namespace App\Rules;

use Closure;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\ValidationRule;

class ReservationConflictRule implements ValidationRule
{
    protected $clinicId;
    protected $reservationId;

    public function __construct(?string $clinicId = null, ?Reservation $reservation = null)
    {
        $this->clinicId = $clinicId ?? Auth::user()->clinic_id;
        $this->reservationId = $reservation?->id;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  Closure  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = Reservation::query()
            ->where('clinic_id', $this->clinicId)
            ->where(function ($q) use ($value) {
                $q->where('start', '<', $this->getEndTime($value))
                  ->where('end', '>', $value);
            });

        if ($this->reservationId) {
            $query->where('id', '!=', $this->reservationId);
        }

        if ($query->exists()) {
            $fail('The reservation conflicts with an existing reservation.');
        }
    }

    protected function getEndTime($startTime)
    {
        // Assuming the end time is calculated based on the start time
        // You can adjust this logic based on your application's requirements
        return date('Y-m-d H:i:s', strtotime($startTime) + 3600); // Example: 1 hour after start
    }
}
