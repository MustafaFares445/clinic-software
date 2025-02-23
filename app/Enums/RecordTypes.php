<?php

namespace App\Enums;

enum RecordTypes: string
{
    case SURGERY = 'surgery';
    case APPOINTMENT = 'appointment';
    case INSPECTION = 'inspection';
}
