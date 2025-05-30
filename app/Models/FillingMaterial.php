<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FillingMaterial extends Model
{
    protected $fillable = [
        'name',
        'color',
        'laboratory_id'
    ];

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }
}
