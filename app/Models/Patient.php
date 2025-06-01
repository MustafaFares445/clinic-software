<?php

namespace App\Models;

use App\Models\Clinic;
use App\Models\Record;
use App\Models\Reservation;
use App\Traits\HasThumbnail;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Database\Factories\PatientFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\PatientClinicScope;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property-read string $id
 * @property-read string $firstName
 * @property-read string $lastName
 * @property-read string $phone
 * @property-read string|null $whatsapp
 * @property-read string $fatherName
 * @property-read string $motherName
 * @property-read string $nationalNumber
 * @property-read string $address
 * @property-read string $clinic_id
 * @property-read string|null $notes
 * @property-read string $birth
 * @property-read string $gender
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 * @property-read Carbon|null $deleted_at
 * @property-read Clinic $clinic
 * @property-read Collection<Record> $records
 * @property-read Collection<Reservation> $reservations
 * @property-read Collection<ChronicDiseas> $chronicDiseases
 * @property-read Collection<ChronicMedication> $chronicMedications
 * @property-read Collection<MedicalCase> $medicalCases
 */
final class Patient extends Model implements HasMedia
{
    /** @use HasFactory<PatientFactory> */
    use HasFactory , HasThumbnail , HasUuids , SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'firstName',
        'lastName',
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

    /**
     * Get the clinic that owns the patient.
     *
     * @return BelongsTo<Clinic, self>
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get the records for the patient.
     *
     * @return HasMany<Record>
     */
    public function records(): HasMany
    {
        return $this->hasMany(Record::class);
    }

    /**
     * Get the reservations for the patient.
     *
     * @return HasMany<Reservation>
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class)->orderByDesc('start');
    }

    /**
     * Get the chronic diseases for the patient.
     *
     * @return HasMany<ChronicDiseas>
     */
    public function chronicDiseases(): HasMany
    {
        return $this->hasMany(ChronicDiseas::class);
    }

    /**
     * Get the chronic medications for the patient.
     *
     * @return HasMany<ChronicMedication>
     */
    public function chronicMedications(): HasMany
    {
        return $this->hasMany(ChronicMedication::class);
    }

    /**
     * Get the medical cases for the patient.
     *
     * @return HasMany<MedicalCase , self>
     */
    public function medicalCases(): HasMany
    {
        return $this->hasMany(MedicalCase::class);
    }
}
