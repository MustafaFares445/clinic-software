<?php

namespace App\Models;

use App\Trait\HasThumbnail;
use Database\Factories\PlanFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;

final class Plan extends Model implements HasMedia
{
    /** @use HasFactory<PlanFactory> */
    use HasFactory , HasThumbnail , HasUuids , SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'fixed_value',
        'percent_value',
        'users_count',
        'duration',
    ];

    public function clinics(): BelongsToMany
    {
        return $this->belongsToMany(Clinic::class);
    }
}
