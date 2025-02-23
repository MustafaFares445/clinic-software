<?php

namespace App\Enums;

enum ReservationTypes: string
{
    case SURGERY = 'surgery';
    case APPOINTMENT = 'appointment';
    case INSPECTION = 'inspection';
}
