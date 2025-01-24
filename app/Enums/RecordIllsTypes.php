<?php

namespace App\Enums;

enum RecordIllsTypes
{
    CONST TRANSIENT = 'transient';
    CONST DIAGNOSED =  'diagnosed';

    public static function values(): array
    {
        return [
            self::TRANSIENT,
            self::DIAGNOSED,
        ];
    }
}
