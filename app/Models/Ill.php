<?php

namespace App\Models;

use App\Trait\HasThumbnail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;

final class Ill extends Model implements HasMedia
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

    public function records(): BelongsToMany
    {
        return $this->belongsToMany(Record::class);
    }

    public function medicalTransactions()
    {
        return $this->morphMany(MedicalTransactions::class , 'model');
    }
}
