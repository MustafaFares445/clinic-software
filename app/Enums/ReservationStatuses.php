<?php

namespace App\Enums;

enum ReservationStatuses: string
{
    case INCOME = 'income';
    case CHECK = 'check';
    case DISMISS = 'dismiss';
    case CANCELLED = 'cancelled';
}
