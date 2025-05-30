<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tooth extends Model
{
    /** @use HasFactory<\Database\Factories\ToothFactory> */
    use HasFactory;

    protected $table = 'teeth';

    protected $fillable = [
        'id',
        'name',
        'number',
        'type',
    ];

    protected $casts = [
        'id' => 'string',
    ];
}
