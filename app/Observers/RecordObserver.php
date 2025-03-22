<?php

namespace App\Observers;

use App\Models\Record;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

final class RecordObserver
{
    /**
     * Handle the Record "created" event.
     */
    public function creating(Record $record): void
    {
        if (request()->has('reservationId')) {
            $reservation = Reservation::query()->select(['id', 'created_at'])->find(request()->input('reservationId'));
        }

        $record->dateTime = $reservation?->created_at ?? now();

        if(Auth::check()){
            $record->clinic_id = request()->input('clinicId') ?? Auth::user()->clinic_id;
        }
    }
}
