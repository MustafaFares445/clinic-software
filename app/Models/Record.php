<?php

namespace App\Models;

use App\Models\Ill;
use App\Models\User;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Medicine;
use App\Enums\RecordTypes;
use App\Models\Reservation;
use Carbon\CarbonImmutable;
use Spatie\MediaLibrary\HasMedia;
use App\Models\Scopes\RecordScope;
use App\Models\MedicalTransactions;
use Illuminate\Support\Facades\Auth;
use Database\Factories\RecordFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
// use Laravel\Scout\Searchable;

/**
 * @property string $id
 * @property string $patient_id
 * @property string $clinic_id
 * @property string|null $reservation_id
 * @property RecordTypes $type
 * @property DateTimeInterface $dateTime
 * @property string|null $notes
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 * @property CarbonImmutable $deleted_at
 * @property-read Patient $patient
 * @property-read Clinic $clinic
 * @property-read Reservation $reservation
 * @property-read Collection<Ill> $ills
 * @property-read Collection<Medicine> $medicines
 * @property-read  Collection<User> $doctors
 * @property-read Collection<MedicalTransactions> $transactions
 */
final class Record extends Model implements HasMedia
{
    /** @use HasFactory<RecordFactory> */
    use HasFactory , HasUuids , InteractsWithMedia , SoftDeletes;

    protected $fillable = [
        'patient_id',
        'clinic_id',
        'reservation_id',
        'type',
        'dateTime',
        'notes',
        'teeth_id'
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
     *  @return BelongsToMany<Patient>
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the clinic that owns the record
     * @return BelongsToMany<Clinic>
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get the reservation that owns the record
     *  @return BelongsToMany<Reservation>
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Get the ills associated with the record
     * @return BelongsToMany<Ill>
     */
    public function ills(): BelongsToMany
    {
        return $this->belongsToMany(Ill::class, 'ill_record')
            ->withPivot(['id' ,'type' , 'notes']);
    }

     /**
     * Get the tooth associated with the record
     * @return BelongsTo<Tooth>
     */
    public function tooth() : BelongsTo
    {
        return $this->belongsTo(Tooth::class);
    }

    /**
     * Get the procedure associated with the record
     * @return BelongsToMany<Procedure>
     */
    public function procedures() : BelongsToMany
    {
        return $this->belongsToMany(Procedure::class, 'procedure_record')
            ->withPivot(['notes']);
    }

    /**
     * Get the medicines associated with the record
     * @return BelongsToMany<Medicine>
     */
    public function medicines(): BelongsToMany
    {
        return $this->belongsToMany(Medicine::class, 'medicine_record')
            ->withPivot(['id', 'notes', 'type']);
    }

    /**
     * Get the doctors associated with the record
     * @return BelongsToMany<User>
     */
    public function doctors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'doctor_record', 'record_id', 'doctor_id');
    }

    /**
     * Get the transactions associated with the record
     * @return HasMany<MedicalTransactions>
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(MedicalTransactions::class);
    }
}
