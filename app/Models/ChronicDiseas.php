<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ChronicDiseas extends Model
{
    use HasUuids;

    protected $table = 'chronic_diseases';
    protected $fillable = [
        'patient_id',
        'description'
    ];

    public function patient() : BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
