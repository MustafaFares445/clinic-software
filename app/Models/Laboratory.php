<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Laboratory
 *
 * Represents a laboratory entity in the system.
 *
 * @package App\Models
 */
class Laboratory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'address',
        'phone',
        'whatsapp',
    ];

    /**
     * Get the fillings associated with the laboratory.
     *
     * @return HasMany
     */
    public function fillings(): HasMany
    {
        return $this->hasMany(Filling::class);
    }
}