<?php

namespace App\Enums;

enum ReservationStatuses
{
    CONST INCOME = 'income';
    CONST CHECK = 'check';
    CONST DISMISS = 'dismiss';
    CONST CANCELLED = 'cancelled';

    public static function values()
    {
        return [
          self::INCOME,
          self::CHECK,
          self::DISMISS,
          self::CANCELLED
        ];
    }
}
