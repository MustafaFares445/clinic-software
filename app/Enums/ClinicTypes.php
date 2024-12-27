<?php

namespace App\Enums;

enum ClinicTypes
{
    CONST HOSPITAL = 'hospital';
    CONST CLINIC = 'clinic';
    CONST HEALTH_CENTER = 'health center';

    public static function values()
    {
        return [
          self::HOSPITAL,
          self::CLINIC,
          self::HEALTH_CENTER
        ];
    }
}
