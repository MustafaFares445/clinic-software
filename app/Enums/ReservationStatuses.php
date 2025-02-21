<?php

namespace App\Enums;

enum ReservationStatuses : string
{
    CASE INCOME = 'income';
    CASE CHECK = 'check';
    CASE DISMISS = 'dismiss';
    CASE CANCELLED = 'cancelled';
}
