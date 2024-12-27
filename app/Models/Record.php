<?php

namespace App\Models;

use Database\Factories\RecordFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Record extends Model implements HasMedia
{
    /** @use HasFactory<RecordFactory> */
    use HasFactory , InteractsWithMedia , SoftDeletes;


    protected $fillable = [
      'patient_id',
      'clinic_id',
      'reservation_id',
      'description',
      'type',
      'price',
    ];

    protected static function booted(): void
    {
        if (Auth::check() && !Auth::user()->hasRole('super admin'))
            self::query()->where('clinic_id' , Auth::user()->clinic_id);
    }


    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function ills(): BelongsToMany
    {
        return $this->belongsToMany(Ill::class);
    }

    public function medicines(): BelongsToMany
    {
        return $this->belongsToMany(Medicine::class);
    }

    public function doctors(): BelongsToMany
    {
        return $this->belongsToMany(User::class , 'record_user' , 'record_id' , 'user_id');
    }
    public function transaction(): MorphMany
    {
        return $this->morphMany(Transaction::class , 'relateable');
    }
}
