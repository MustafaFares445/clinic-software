<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use App\Models\Clinic;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Represents a working day for a clinic
 * @property Uuid $id The unique key for this model
 * @property Uuid $clinic_id The UUID of the associated clinic
 * @property string $day The day of the week (e.g., mon, tue)
 * @property CarbonImmutable $start The start time of the working day (format: HH:mm)
 * @property CarbonImmutable $end The end time of the working day (format: HH:mm)
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 * @property-read Clinic $clinic The associated clinic
 */
class ClinicWorkingDay extends Model
{
    /** @use HasFactory<\Database\Factories\ClinicWorkingDayFactory> */
    use HasFactory , HasUuids;

    protected $fillable = [
        'clinic_id',
        'day',
        'start',
        'end'
    ];

    protected $casts = [
        'clinic_id' => 'string',
        'start' => 'datetime:H:i',
        'end' => 'datetime:H:i',
    ];

    /**
     * Get the clinic associated with this working day
     *
     * @return BelongsTo<Clinic , self>
     */
    public function clinic() : BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }
}
