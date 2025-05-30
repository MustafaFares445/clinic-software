<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Represents a dental filling in the system.
 *
 * @property string $name The name of the filling.
 * @property int $clinic_id The ID of the clinic associated with the filling.
 * @property int $laboratory_id The ID of the laboratory associated with the filling.
 * @property float $price The price of the filling.
 */
class Filling extends Model
{
    /** @use HasFactory<\Database\Factories\FillingFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'clinic_id',
        'laboratory_id',
        'price',
    ];

    /**
     * Get the clinic associated with the filling.
     *
     * @return BelongsTo
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get the laboratory associated with the filling.
     *
     * @return BelongsTo
     */
    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }
}
