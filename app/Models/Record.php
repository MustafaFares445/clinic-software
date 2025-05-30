<?php

namespace App\Models;

use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\Patient;
use Spatie\MediaLibrary\HasMedia;
use App\Models\Scopes\RecordScope;
use Database\Factories\RecordFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
// use Laravel\Scout\Searchable;

/**
 * @property-read Uuid $id
 * @property-read string $type
 * @property-read string $description
 * @property-read Patient $patient
 * @property-read Tooth $tooth
 * @property-read Treatment $treatment
 * @property-read Patient $patient
 * @property-read FillingMaterial $fillingMaterial
 * @property-read MedicalSession $medicalSession
 * @property-read Collection<User> $doctors
 */
final class Record extends Model implements HasMedia
{
    /** @use HasFactory<RecordFactory> */
    use HasFactory , HasUuids , InteractsWithMedia , SoftDeletes;

    protected $fillable = [
      'description',
      'type',

      'treatment_id',
      'tooth_id',
      'clinic_id',
      'filling_material_id',
      'medical_seesion_id',
      'patient_id'
    ];

    /**
     * Boot the model and add global scopes
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new RecordScope());
    }

    /**
     * Get the name of the search index
     */
    public function searchableAs()
    {
        return 'record_index';
    }

    /**
     * Convert the model to a searchable array
     */
    public function toSearchableArray()
    {
        return [
            'id' => (string) $this->id,
            'notes' => $this->notes ?? '',
            'type' => $this->type ?? '',
            'created_at' => $this->created_at ? $this->created_at->timestamp : 0,
        ];
    }

    /**
     * Get the patient that owns the record
     * @return BelongsTo<Patient>
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the tooth associated with the record
     * @return BelongsTo<Tooth>
     */
    public function tooth(): BelongsTo
    {
        return $this->belongsTo(Tooth::class);
    }

    /**
     * Get the treatment associated with the record
     * @return BelongsTo<Treatment>
     */
    public function treatment(): BelongsTo
    {
        return $this->belongsTo(Treatment::class);
    }

    /**
     * Get the filling material associated with the record
     * @return BelongsTo<FillingMaterial>
     */
    public function fillingMaterial(): BelongsTo
    {
        return $this->belongsTo(FillingMaterial::class);
    }

    /**
     * Get the medical session associated with the record
     * @return BelongsTo<MedicalSession>
     */
    public function medicalSession(): BelongsTo
    {
        return $this->belongsTo(MedicalSession::class);
    }

    /**
     * Get the doctors associated with the record
     * @return BelongsToMany<User>
     */
    public function doctors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'doctor_record', 'record_id', 'doctor_id');
    }
}
