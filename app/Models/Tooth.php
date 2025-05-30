<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tooth extends Model
{
    /** @use HasFactory<\Database\Factories\ToothFactory> */
    use HasFactory , HasUuids;

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

    public function records()
    {
        return $this->hasMany(Record::class);
    }
}
