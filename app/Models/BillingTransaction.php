<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use App\Models\User;
use App\Models\Clinic;
use Carbon\CarbonImmutable;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Database\Factories\BillingTransactionFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Represents a billing transaction in the system.
 *
 * @property Uuid $id
 * @property Uuid $clinic_id
 * @property string $type Possible values: in, out
 * @property float $amount
 * @property string|null $description
 * @property string $user_id
 * @property string $model_id
 * @property string $model_type Possible values: MedicalTransaction::class, Reservation::class
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Clinic $clinic
 * @property-read User $user
 * @property-read MedicalTransaction|Reservation $model
 * @property-read MediaCollection|Media[] $media
 */
final class BillingTransaction extends Model implements HasMedia
{
    /** @use HasFactory<BillingTransactionFactory> */
    use HasFactory, HasUuids, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'clinic_id',
        'type',
        'amount',
        'description',
        'user_id',
        'model_id',
        'model_type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'float',
    ];

    /**
     * The "booted" method of the model.
     *
     * Adds a global scope to filter transactions by clinic_id for non-super admin users.
     *
     * @return void
     */
    protected static function booted(): void
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && ! $user->hasRole('super admin')) {
            static::addGlobalScope('clinic', function ($builder) use ($user) {
                $builder->where('clinic_id', $user->clinic_id);
            });
        }
    }

    /**
     * Get the clinic that owns the transaction.
     *
     * @return BelongsTo<Clinic, self>
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get the user that created the transaction.
     *
     * @return BelongsTo<User, self>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the associated model.
     *
     * @return MorphTo<Model, self>
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
