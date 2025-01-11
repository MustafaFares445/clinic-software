<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MedicineTransaction extends Model implements HasMedia
{
    use HasFactory , InteractsWithMedia , SoftDeletes , HasUuids;

    protected $table = 'medicine_transactions';
    protected $fillable = [
        'clinic_id',
        'medicine_id',
        'quantity'
    ];

    protected static function booted(): void
    {
        if (Auth::check() && !Auth::user()->hasRole('super admin') && !request()->has('clinicId'))
            self::query()->where('clinic_id' , Auth::user()->clinic_id);
    }


    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    public function transaction(): MorphMany
    {
        return $this->morphMany(Transaction::class , 'relateable');
    }
}
