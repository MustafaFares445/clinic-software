<?php

namespace App\Models;

use App\Models\Ill;
use App\Models\Clinic;
use App\Models\Record;
use App\Models\Medicine;
use App\Models\Reservation;
use App\Traits\HasThumbnail;
use Spatie\MediaLibrary\HasMedia;
use Database\Factories\PatientFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\PatientClinicScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


final class Patient extends Model implements HasMedia
{
    /** @use HasFactory<PatientFactory> */
    use HasFactory , HasThumbnail , HasUuids , SoftDeletes;

    protected $fillable = [
        'firstName',
        'lastName',
        'full_name',
        'phone',
        'whatsapp',
        'fatherName',
        'motherName',
        'nationalNumber',
        'address',
        'clinic_id',
        'notes',
        'birth',
        'gender',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new PatientClinicScope());
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
        return $this->hasMany(Reservation::class)->orderByDesc('start');
    }

    public function permanentIlls(): BelongsToMany
    {
        return $this->belongsToMany(Ill::class, 'ill_patient')->withPivot('notes');
    }

    public function permanentMedicines(): BelongsToMany
    {
        return $this->belongsToMany(Medicine::class, 'medicine_patient')->withPivot('notes');
    }
}
