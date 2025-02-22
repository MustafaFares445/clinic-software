<?php

namespace App\Models;

use Database\Factories\ReservationFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Reservation extends Model implements HasMedia
{
    /** @use HasFactory<ReservationFactory> */
    use HasFactory , InteractsWithMedia , HasUuids;

    protected $fillable = [
      'start',
      'end',
      'specification_id',
      'patient_id',
      'clinic_id',
      'doctor_id',
      'type',
      'status',
    ];

    protected static function booted(): void
    {
        if (Auth::check() && !Auth::user()->hasRole('super admin') && !request()->has('clinicId'))
            self::query()->where('clinic_id' , Auth::user()->clinic_id);

        if (request()->has('clinicId'))
            self::query()->where('clinic_id' , request()->input('clinicId'));

        if (Auth::check() && Auth::user()->hasRole('doctor'))
            self::query()->where('doctor_id' , Auth::id());
    }


    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function specification(): BelongsTo
    {
        return $this->belongsTo(Specification::class);
    }
}
