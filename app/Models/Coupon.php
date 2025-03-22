<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Carbon\CarbonImmutable;
use Database\Factories\CopounFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Represents a coupon in the system.
 *
 * @property Uuid $id UUID of the coupon
 * @property string $code Unique coupon code
 * @property int $fixed_value Fixed discount amount in cents/smallest currency unit
 * @property int $percent_value Percentage discount value
 * @property DateTimeInterface $expire_at Expiration date and time of the coupon
 * @property Uuid $plan_id UUID of the associated plan
 * @property int $used_number Number of times the coupon can be used
 * @property bool $is_active Whether the coupon is currently active
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 * @property Plan $plan
 */
final class Coupon extends Model
{
    /** @use HasFactory<CopounFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'code',
        'fixed_value',
        'percent_value',
        'expire_at',
        'plan_id',
        'used_number',
        'is_active',
    ];

    /**
     * Get the plan associated with the coupon.
     *
     * @return BelongsTo<Plan, self>
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
