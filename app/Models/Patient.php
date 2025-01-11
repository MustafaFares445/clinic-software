<?php

namespace App\Models;

use Database\Factories\PatientFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Patient extends Model implements HasMedia
{
    /** @use HasFactory<PatientFactory> */
    use HasFactory , InteractsWithMedia , SoftDeletes , HasUuids;

    protected $fillable = [
      'firstName',
      'lastName',
      'phone',
      'age',
      'fatherName',
      'motherName',
      'nationalNumber',
      'address',
      'clinic_id',
      'description',
    ];

    protected static function booted(): void
    {
        if (Auth::check() && !Auth::user()->hasRole('super admin') && !request()->has('clinicId'))
            self::query()->where('clinic_id' , Auth::user()->clinic_id);
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function records(): HasMany
    {
        return $this->hasMany(Record::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class)->orderByDesc('start_date');
    }
}
