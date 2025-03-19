<?php

namespace App\Models;

use Database\Factories\RecordFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

final class Record extends Model implements HasMedia
{
    /** @use HasFactory<RecordFactory> */
    use HasFactory , HasUuids , InteractsWithMedia , SoftDeletes;

    protected $fillable = [
        'patient_id',
        'clinic_id',
        'reservation_id',
        'description',
        'type',
        'dateTime',
    ];

    protected static function booted(): void
    {
        if (Auth::check() && ! Auth::user()->hasRole('super admin') && ! request()->has('clinicId')) {
            self::query()->where('clinic_id', Auth::user()->clinic_id);
        }

        if (request()->has('clinicId')) {
            self::query()->where('clinic_id', request()->input('clinicId'));
        }

        if (Auth::check() && Auth::user()->hasRole('doctor')) {
            self::query()->whereRelation('doctors', 'doctor_id', '=', Auth::id());
        }
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
        return $this->belongsToMany(Ill::class, 'ill_record')->withPivot('type');
    }

    public function medicines(): BelongsToMany
    {
        return $this->belongsToMany(Medicine::class, 'medicine_record')
            ->using(MedicineRecord::class)
            ->withPivot(['id', 'note', 'type']);
    }

    public function doctors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'doctor_record', 'record_id', 'doctor_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(MedicalTransactions::class);
    }
}
