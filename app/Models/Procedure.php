<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use App\Models\Clinic;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Procedure
 *
 * Represents a medical procedure in the system.
 * @property Uuid $id
 * @property string $title The title of the procedure.
 * @property string $description The description of the procedure.
 * @property string $clinic_id The ID of the clinic associated with the procedure.
 * @property string $color The color of the procedure.
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
class  Procedure extends Model
{
    /** @use HasFactory<\Database\Factories\ProcedureFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'description',
        'clinic_id',
        'filling_id',
        'color',
        'price',
    ];

    /**
     * Get the clinic associated with the procedure.
     *
     * @return BelongsTo
     */
    public function clinic() : BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get the filling associated with the procedure.
     *
     * @return BelongsTo
     */
    public function filling() : BelongsTo
    {
        return $this->belongsTo(Filling::class);
    }
}
