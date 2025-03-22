<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Database\Factories\ReservationFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use DateTimeInterface;
use App\Enums\ReservationTypes;
use App\Enums\ReservationStatuses;

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
 * @property-read Specification $specification The associated medical specification
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
        'specification_id',
        'patient_id',
        'clinic_id',
        'doctor_id',
        'type',
        'status',
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

        if (Auth::check() && ! $user->hasRole('super admin') && ! request()->has('clinicId')) {
            self::query()->where('clinic_id', $user->clinic_id);
        }

        if (request()->has('clinicId')) {
            self::query()->where('clinic_id', request()->input('clinicId'));
        }

        if (Auth::check() && $user->hasRole('doctor')) {
            self::query()->where('doctor_id', $user->id);
        }
    }

    /**
     * Get the patient associated with the reservation.
     *
     * @return BelongsTo<Patient, Reservation>
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the clinic associated with the reservation.
     *
     * @return BelongsTo<Clinic, Reservation>
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get the doctor associated with the reservation.
     *
     * @return BelongsTo<User, Reservation>
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the medical specification associated with the reservation.
     *
     * @return BelongsTo<Specification, Reservation>
     */
    public function specification(): BelongsTo
    {
        return $this->belongsTo(Specification::class);
    }
}
