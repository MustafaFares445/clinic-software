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
        'clinic_id'
    ];

    /**
     * Get the fillings associated with the laboratory.
     *
     * @return HasMany
     */
    public function fillingMaterials(): HasMany
    {
        return $this->hasMany(fillingMaterial::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}