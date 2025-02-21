<?php

namespace App\Models;

use App\Trait\HasThumbnail;
use Spatie\MediaLibrary\HasMedia;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Specification extends Model implements HasMedia
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory , HasThumbnail , SoftDeletes , HasUuids;

    protected $fillable = [
      'name',
      'description',
      'parent_id'
    ];

    public function scopeRoot(Builder $query): void
    {
        $query->whereNull('parent_id');
    }

    public function children()
    {
        return $this->hasMany(Specification::class , 'parent_id');
    }

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
