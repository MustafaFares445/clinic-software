<?php

namespace App\Models;

use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

final class BillingTransaction extends Model implements HasMedia
{
    /** @use HasFactory<TransactionFactory> */
    use HasFactory , HasUuids , InteractsWithMedia;

    protected $fillable = [
        'clinic_id',
        'type',
        'amount',
        'description',
        'user_id',
    ];

    protected static function booted(): void
    {
        if (Auth::check() && ! Auth::user()->hasRole('super admin')) {
            self::query()->where('clinic_id', Auth::user()->clinic_id);
        }
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
