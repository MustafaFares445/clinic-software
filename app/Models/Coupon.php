<?php

namespace App\Models;

use Database\Factories\CopounFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    /** @use HasFactory<CopounFactory> */
    use HasFactory , SoftDeletes , HasUuids;

    protected $fillable = [
        'code',
        'fixed_value',
        'percent_value',
        'expire_at',
        'plan_id',
        'used_number',
        'is_active'
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
