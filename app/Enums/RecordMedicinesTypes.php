<?php

namespace App\Enums;

enum RecordMedicinesTypes
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
