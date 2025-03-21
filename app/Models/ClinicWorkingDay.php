<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicWorkingDay extends Model
{
    /** @use HasFactory<\Database\Factories\ClinicWorkingDayFactory> */
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'day',
        'start',
        'end'
    ];

    protected $casts = [
        'start' => 'datetime:H:i',
        'end' => 'datetime:H:i',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
