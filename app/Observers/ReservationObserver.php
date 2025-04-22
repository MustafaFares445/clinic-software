<?php

namespace App\Observers;

use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class ReservationObserver
{
     /**
     * Handle the Record "created" event.
     */
    public function creating(Reservation $reservation): void
    {
        if(Auth::check()) $reservation->clinic_id = request()->input('clinicId') ?? Auth::user()->clinic_id;
    }
}
