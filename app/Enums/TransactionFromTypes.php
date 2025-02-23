<?php

namespace App\Enums;

enum TransactionFromTypes: string
{
    case MEDICINE = 'medicine';
    case RECORD = 'record';
    case EQUIPMENT = 'equipment';
}
