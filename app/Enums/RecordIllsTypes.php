<?php

namespace App\Enums;

enum RecordIllsTypes: string
{
    case TRANSIENT = 'transient'; // عابر
    case DIAGNOSED = 'diagnosed'; // تم تشخيصه
}
