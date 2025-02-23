<?php

namespace App\Models;

use Database\Factories\OfferFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

final class Offer extends Model implements HasMedia
{
    /** @use HasFactory<OfferFactory> */
    use HasFactory , HasUuids , InteractsWithMedia , SoftDeletes;

    protected $fillable = [
        'name',
        'start',
        'end',
        'fixed_value',
        'percent_value',
    ];

    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class);
    }
}
