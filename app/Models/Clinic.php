<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\ClinicFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Clinic extends Model implements HasMedia
{
    /** @use HasFactory<ClinicFactory> */
    use HasFactory , InteractsWithMedia , SoftDeletes;

    protected $fillable = [
      'name',
      'address',
      'longitude',
      'latitude',
      'description',
      'is_banned',
      'type'
    ];

    public function currentPlan(): ?Model
    {
        $currentPlan = $this->belongsToMany(Plan::class)->latest()->first();
        return ( now() > (Carbon::parse($currentPlan?->created_at +  $currentPlan?->duration)) ) ? $currentPlan : null;
    }

    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class);
    }

    public function coupons(): BelongsToMany
    {
        return $this->belongsToMany(Coupon::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function transaction(): MorphMany
    {
        return $this->morphMany(Transaction::class , 'relateable');
    }

    public function medicineTransactions(): HasMany
    {
        return $this->hasMany(MedicineTransaction::class)->with('medicine');
    }
}
