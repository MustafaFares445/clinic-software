<?php

namespace App\Models;

use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\Clinic;
use App\Models\Record;
use App\Models\Medicine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class MedicalTransactions
 *
 * Represents medical transactions in the system, including medicine dispensing and other medical procedures.
 *
 * @property Uuid $id UUID of the transaction
 * @property int $quantity Quantity of medicine or service
 * @property string $type Type of medical transaction (in , out)
 * @property Uuid $record_id Related medical record ID
 * @property Uuid $clinic_id Associated clinic ID
 * @property string|null $description Description of the transaction
 * @property Uuid $medicine_id Related medicine ID
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable|null $updated_at
 *
 * @property-read Model $model The parent model (polymorphic)
 * @property-read Clinic $clinic The associated clinic
 * @property-read User $doctor The doctor who performed the transaction
 * @property-read Record $record The associated medical record
 * @property-read Medicine $medicine The associated medicine
 */
class MedicalTransactions extends Model
{
    /** @use HasFactory<\Database\Factories\MedicalTransactionsFactory> */
    use HasFactory , HasUuids;

    protected $fillable = [
        'quantity',
        'type',
        'record_id',
        'clinic_id',
        'description',
        'medicine_id'
    ];

    /**
     * Get the parent model (polymorphic relationship).
     *
     * @return MorphTo<Model>
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the clinic associated with the transaction.
     *
     * @return BelongsTo<Clinic, self>
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get the doctor who performed the transaction.
     *
     * @return BelongsTo<User, self>
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Get the medical record associated with the transaction.
     *
     * @return BelongsTo<Record, self>
     */
    public function record(): BelongsTo
    {
        return $this->belongsTo(Record::class);
    }

    /**
     * Get the medicine associated with the transaction.
     *
     * @return BelongsTo<Medicine, self>
     */
    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }
}
