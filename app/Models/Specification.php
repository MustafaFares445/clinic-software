<?php

namespace App\Models;

use App\Trait\HasThumbnail;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Specification extends Model implements HasMedia
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory , HasThumbnail , SoftDeletes , HasUuids;

    protected $fillable = [
      'name',
      'description'
    ];

    public function clinics(): HasMany
    {
        return $this->hasMany(Clinic::class);
    }
    public function ills(): HasMany
    {
        return $this->hasMany(Ill::class);
    }

    public function medicines(): HasMany
    {
        return $this->hasMany(Medicine::class);
    }
}
