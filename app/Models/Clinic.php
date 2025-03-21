<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\User;
use App\Models\Coupon;
use App\Trait\HasThumbnail;
use App\Models\Specification;
use App\Models\ClinicWorkingDay;
use Spatie\MediaLibrary\HasMedia;
use Database\Factories\ClinicFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Clinic model representing a medical clinic
 *
 * @property string $id
 * @property string $name
 * @property string $address
 * @property float $longitude
 * @property float $latitude
 * @property string|null $description
 * @property bool $is_banned
 * @property string $type
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
final class Clinic extends Model implements HasMedia
{
    /** @use HasFactory<ClinicFactory> */
    use HasFactory , HasThumbnail , HasUuids , SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'longitude',
        'latitude',
        'description',
        'is_banned',
        'type',
    ];

    protected $casts = [
        'longitude' => 'float',
        'latitude' => 'float',
        'is_banned' => 'boolean',
    ];

    /**
     * Get the clinic's current active plan
     *
     * @return HasOne<Plan>
     */
    public function currentPlan(): HasOne
    {
        return $this->hasOne(Plan::class)
            ->where('created_at', '<=', now())
            ->where('created_at', '>=', now()->subDays($this->plans()->first()?->duration ?? 0))
            ->latest();
    }

    /**
     * Get all plans associated with the clinic
     *
     * @return BelongsToMany<Plan>
     */
    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class);
    }

    /**
     * Get all coupons associated with the clinic
     *
     * @return BelongsToMany<Coupon>
     */
    public function coupons(): BelongsToMany
    {
        return $this->belongsToMany(Coupon::class);
    }

    /**
     * Get all specifications associated with the clinic
     *
     * @return BelongsToMany<Specification>
     */
    public function specifications(): BelongsToMany
    {
        return $this->belongsToMany(Specification::class);
    }

    /**
     * Get all users associated with the clinic
     *
     * @return HasMany<User>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the clinic's working days
     *
     * @return HasMany<ClinicWorkingDay>
     */
    public function workingDays(): HasMany
    {
        return $this->hasMany(ClinicWorkingDay::class);
    }
}
