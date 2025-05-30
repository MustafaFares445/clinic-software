<?php

namespace App\Models;

use App\Traits\HasThumbnail;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;
use Spatie\MediaLibrary\HasMedia;

/**
 * Class Ill
 *
 * Represents a medical condition or illness in the system.
 * This model uses UUIDs as primary keys and supports soft deletes.
 * It also implements media management through Spatie Media Library.
 *
 * @property Uuid $id UUID primary key
 * @property string $name Name of the illness
 * @property string|null $description Detailed description of the illness
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 */
final class Ill extends Model implements HasMedia
{
    use HasFactory , HasThumbnail , HasUuids , SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'clinic_id',
    ];

    /**
     * Get the specifications associated with this illness.
     *
     * @return BelongsToMany<Specification, self>
     */
    public function specifications(): BelongsToMany
    {
        return $this->BelongsToMany(Specification::class);
    }

    /**
     * Get the medical records associated with this illness.
     *
     * @return BelongsToMany<Record, self>
     */
    public function records(): BelongsToMany
    {
        return $this->belongsToMany(Record::class);
    }

    /**
     * Get the medical transactions associated with this illness.
     * This is a polymorphic relationship.
     *
     * @return MorphMany<MedicalTransactions, self>
     */
    public function medicalTransactions() : MorphMany
    {
        return $this->morphMany(MedicalTransactions::class , 'model');
    }

    public function clinic()
}
