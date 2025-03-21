<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalTransactions extends Model
{
    /** @use HasFactory<\Database\Factories\MedicalTransactionsFactory> */
    use HasFactory , HasUuids;

    protected $fillable = [
        'quantity',
        'type',
        'record_id',
        'clinic_id',
        'description',
        'medicine_id'
    ];

    public function model() : MorphTo
    {
        return $this->morphTo();
    }

    public function clinic() : BelongsTo
    {
       return $this->belongsTo(Clinic::class);
    }

    public function doctor() : BelongsTo
    {
        return $this->belongsTo(User::class , 'doctor_id');
    }

    public function record() : BelongsTo
    {
        return $this->belongsTo(Record::class);
    }

    public function medicine() : BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }
}
