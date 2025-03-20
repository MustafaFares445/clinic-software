<?php

namespace App\Models;

use App\Models\User;
use App\Models\Clinic;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Database\Factories\BillingTransactionFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Represents a billing transaction in the system.
 *
 * @property string $id
 * @property string $clinic_id
 * @property string $type
 * @property float $amount
 * @property string|null $description
 * @property string $user_id
 * @property string $model_id
 * @property string $model_type
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
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
