<?php

namespace App\Models;

use App\Traits\HasThumbnail;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;
use Spatie\MediaLibrary\HasMedia;

/**
 * Class Medicine
 *
 * Represents a medicine/pharmaceutical product in the system.
 * This model uses UUIDs as primary keys and supports soft deletes.
 * It also implements media management through Spatie Media Library.
 *
 * @property Uuid $id UUID primary key
 * @property string $name Name of the medicine
 * @property string|null $description Detailed description of the medicine
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 *
 * @property-read Collection<Specification> $specifications
 * @property-read Collection<Clinic> $clinics
 * @property-read Collection<MedicalTransactions> $transactions
 */
final class Medicine extends Model implements HasMedia
{
    use HasFactory , HasThumbnail , HasUuids , SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the specifications associated with the medicine.
     *
     * @return BelongsToMany<Specification, self>
     */
    public function specifications(): BelongsToMany
    {
        return $this->BelongsToMany(Specification::class);
    }

    /**
     * Get the clinics that stock this medicine.
     *
     * @return BelongsToMany<Clinic, self>
     */
    public function clinics(): BelongsToMany
    {
        return $this->belongsToMany(Clinic::class);
    }

    /**
     * Get the medical transactions for this medicine.
     *
     * @return HasMany<MedicalTransactions, self>
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(MedicalTransactions::class);
    }
}
