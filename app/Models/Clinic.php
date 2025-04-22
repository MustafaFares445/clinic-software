<?php

namespace App\Models;

use App\Enums\ClinicTypes;
use App\Traits\HasThumbnail;
use Carbon\CarbonImmutable;
use Database\Factories\ClinicFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;
use Spatie\MediaLibrary\HasMedia;

/**
 * Clinic model representing a medical clinic
 *
 * @property Uuid $id
 * @property string $name
 * @property string $address
 * @property float $longitude
 * @property float $latitude
 * @property string|null $description
 * @property bool $is_banned
 * @property ClinicTypes $type
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 * @property-read Plan|null $currentPlan
 * @property-read Collection<Plan> $plans
 * @property-read Collection<Coupon> $coupons
 * @property-read Collection<Specification> $specifications
 * @property-read Collection<User> $users
 * @property-read Collection<ClinicWorkingDay> $workingDays
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
     * @return HasOne<Plan, self>
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
     * @return BelongsToMany<Plan, self>
     */
    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class);
    }

    /**
     * Get all coupons associated with the clinic
     *
     * @return BelongsToMany<Coupon, self>
     */
    public function coupons(): BelongsToMany
    {
        return $this->belongsToMany(Coupon::class);
    }

    /**
     * Get all specifications associated with the clinic
     *
     * @return BelongsToMany<Specification, self>
     */
    public function specifications(): BelongsToMany
    {
        return $this->belongsToMany(Specification::class);
    }

    /**
     * Get all users associated with the clinic
     *
     * @return HasMany<User, self>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all records associated with the clinic
     *
     * @return HasMany<Clinic, self>
     */
    public function records(): HasMany
    {
        return $this->hasMany(Clinic::class);
    }

    /**
     * Get all records associated with the clinic
     *
     * @return HasMany<Patient, self>
     */
    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }

    /**
     * Get the clinic's working days
     *
     * @return HasMany<ClinicWorkingDay, self>
     */
    public function workingDays(): HasMany
    {
        return $this->hasMany(ClinicWorkingDay::class);
    }
}
