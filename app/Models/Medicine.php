<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Medicine extends Model implements HasMedia
{
    use HasFactory , InteractsWithMedia , SoftDeletes;

    protected $fillable = [
      'name',
      'description'
    ];

    public function categories(): BelongsToMany
    {
        return $this->BelongsToMany(Category::class);
    }

    public function clinics(): BelongsToMany
    {
        return $this->belongsToMany(Clinic::class);
    }

    public function medicineTransaction(): HasMany
    {
        return $this->hasMany(MedicineTransaction::class);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class , 'relateable');
    }
}
