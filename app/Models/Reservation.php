<?php

namespace App\Models;

use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\Clinic;
use DateTimeInterface;
use App\Models\Patient;
use App\Models\MedicalCase;
use App\Enums\ReservationTypes;
use Spatie\MediaLibrary\HasMedia;
use App\Enums\ReservationStatuses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\ReservationFactory;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Reservation
 *
 * Represents a medical reservation/appointment in the system.
 *
 * @property Uuid $id UUID of the reservation
 * @property DateTimeInterface $start Start time of the reservation
 * @property DateTimeInterface $end End time of the reservation
 * @property Uuid $specification_id ID of the medical specification
 * @property Uuid $patient_id ID of the patient
 * @property Uuid $clinic_id ID of the clinic
 * @property Uuid $doctor_id ID of the doctor
 * @property ReservationTypes $type Type of the reservation
 * @property ReservationStatuses $status Status of the reservation
 * @property-read Patient $patient The associated patient
 * @property-read Clinic $clinic The associated clinic
 * @property-read User $doctor The associated doctor
 * @property-read MedicalCase|null $medicalCase The associated medical case
 */
final class Reservation extends Model implements HasMedia
{
    /**
     * @use HasFactory<ReservationFactory>
     */
    use HasFactory, HasUuids, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'start',
        'end',
        'patient_id',
        'clinic_id',
        'doctor_id',
        'type',
        'status',
        'medical_case_id'
    ];

    /**
     * The "booted" method of the model.
     *
     * Applies global scopes based on user authentication and request parameters:
     * - Filters by clinic_id for non-admin users
     * - Filters by requested clinic_id
     * - Filters by doctor_id for users with doctor role
     *
     * @return void
     */
    protected static function booted(): void
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (Auth::check() && !$user->hasRole('super Admin')) {
            self::query()->whereRelation('clinic', 'id', request()->input('clinicId') ?? Auth::user()->clinic_id);
        }

       if (Auth::check() && $user->hasAllRoles('doctor')) {
           self::query()->whereRelation('doctors', 'doctor_id',  Auth::id());
       }
    }

    /**
     * Get the patient associated with the reservation.
     *
     * @return BelongsTo<Patient, self>
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the clinic associated with the reservation.
     *
     * @return BelongsTo<Clinic, self>
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get the doctor associated with the reservation.
     *
     * @return BelongsTo<User, self>
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the medical case associated with the reservation.
     *
    //  * @return BelongsTo<MedicalCase, self>
     */
    public function medicalCase(): BelongsTo
    {
        return $this->belongsTo(MedicalCase::class , 'medical_case_id');
    }
}
