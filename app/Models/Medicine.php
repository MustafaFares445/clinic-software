<?php

namespace App\Models;

use App\Trait\HasThumbnail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;

final class Medicine extends Model implements HasMedia
{
    use HasFactory , HasThumbnail , HasUuids , SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

    public function specifications(): BelongsToMany
    {
        return $this->BelongsToMany(Specification::class);
    }

    public function clinics(): BelongsToMany
    {
        return $this->belongsToMany(Clinic::class);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'relateable');
    }
}
