<?php

namespace App\Models;

use App\Trait\HasThumbnail;
use Carbon\Carbon;
use Database\Factories\ClinicFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;

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
        'start',
        'end',
    ];

    public function currentPlan(): ?Model
    {
        $currentPlan = $this->belongsToMany(Plan::class)->latest()->first();

        return (now() > (Carbon::parse($currentPlan?->created_at + $currentPlan?->duration))) ? $currentPlan : null;
    }

    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class);
    }

    public function coupons(): BelongsToMany
    {
        return $this->belongsToMany(Coupon::class);
    }

    public function specifications(): BelongsToMany
    {
        return $this->belongsToMany(Specification::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
