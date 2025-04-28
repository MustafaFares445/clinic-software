<?php

namespace App\Actions;

use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

/**
 * Class CheckReservationConflict
 *
 * Provides functionality to check for overlapping reservations in a clinic.
 */
class CheckReservationConflict
{
    /**
     * Check if a reservation conflicts with existing reservations.
     *
     * @param string|null $clinicId The ID of the clinic. If null, uses the authenticated user's clinic ID.
     * @param string $start The start time of the reservation.
     * @param string $end The end time of the reservation.
     * @param string|null $reservationId The ID of the reservation to exclude (e.g., when updating an existing reservation).
     * @return bool True if a conflicting reservation exists, false otherwise.
     */
    public function handle(
        ?string $clinicId,
        string $start,
        string $end,
        ?string $reservationId = null
    ): bool {
        $clinicId = $clinicId ?? Auth::user()->clinic_id;

        $query = Reservation::where('clinic_id', $clinicId)
            ->where(function ($q) use ($start, $end) {
                $q->where('start', '<=', $end)
                  ->where('end', '>=', $start);
            });

        if ($reservationId) {
            $query->where('id', '!=', $reservationId);
        }

        return $query->exists();
    }
}