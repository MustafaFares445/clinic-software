<?php

namespace App\Actions;

use Illuminate\Database\Eloquent\Builder;

final class PatientsOrder
{
    public function order(Builder &$patientQuery): void
    {
        if (request()->input('orderBy') === 'firstName') {
            $patientQuery->orderBy('patients.firstName', request()->input('orderType') ?? 'ASC');
        }
        if (request()->input('orderBy') === 'lastName') {
            $patientQuery->orderBy('patients.lastName', request()->input('orderType') ?? 'ASC');
        }
        if (request()->input('orderBy') === 'registeredAt') {
            $patientQuery->orderBy('patients.created_at', request()->input('orderType') ?? 'ASC');
        } else {
            $this->reservationOrder($patientQuery, request()->input('orderBy'));
        }
    }

    private function reservationOrder(Builder &$patientQuery, ?string $reservationType = 'nextReservation'): void
    {
        if ($reservationType === 'lastReservation') {
            $patientQuery->orderBy('last_reservation.start');
        } else {
            $patientQuery->orderBy('next_reservation.start');
        }
    }
}
