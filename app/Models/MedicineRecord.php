<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Str;

final class MedicineRecord extends Pivot
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected static function boot(): void
    {
        parent::boot();

        // Automatically generate a UUID when creating a new pivot record
        self::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
