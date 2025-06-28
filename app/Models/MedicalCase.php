<?php

namespace App\Models;

use App\Models\Clinic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class MedicalCase
 *
 * @property-read int $id
 * @property-read string $name
 * @property-read string $description
 * @property-read int $clinic_id
 * @property-read float $total
 * @property-read string $date
 * @property-read string $mode
 * @property-read Clinic $clinic
 * @property-read User $createdBy
 */
class MedicalCase extends Model
{
    protected $table = 'medical_cases';

    protected $fillable = [
        'name', 'description', 'clinic_id', 'total', 'date', 'created_by_id' , 'patient_id',
    ];

     /**
     * The "booted" method of the model.
     *
     * Applies global scopes based on user authentication and request parameters:
     * - Filters by clinic_id for non-admin users
     * - Filters by requested clinic_id
     *
     * @return void
     */
    protected static function booted(): void
    {
        /** @var User|null $user */
        $user = Auth::user();

        // if (Auth::check() && !$user->hasRole('super Admin')) {
        //     self::query()->whereRelation('clinic', 'id', request()->input('clinicId') ?? Auth::user()->clinic_id);
        // }
    }

     /**
     * Get the clinic associated with the Medical Case.
     *
     * @return BelongsTo<Clinic, self>
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get the User that created this Medical Case.
     *
     * @return BelongsTo<User, self>
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Patient that associated with the Medical Case.
     *
     * @return BelongsTo<Patient, self>
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function medicalSessions(): HasMany
    {
        return $this->hasMany(MedicalSession::class);
    }

    public function records(): HasMany
    {
        return $this->hasMany(Record::class);
    }
}
