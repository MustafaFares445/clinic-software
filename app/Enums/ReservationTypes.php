<?php

namespace App\Enums;

enum ReservationTypes
{
    CONST SURGERY = 'surgery';
    CONST APPOINTMENT =  'appointment';
    CONST  INSPECTION = 'inspection';

    public static function values(): array
    {
        return [
          self::INSPECTION,
          self::APPOINTMENT,
          self::SURGERY
        ];
    }
}