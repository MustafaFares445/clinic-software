<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    /** @use HasFactory<\Database\Factories\EquipmentFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public function medicalTransactions()
    {
        return $this->morphMany(MedicalTransactions::class , 'model');
    }
}
