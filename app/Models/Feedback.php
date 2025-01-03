<?php

namespace App\Models;

use Database\Factories\FeedbackFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    /** @use HasFactory<FeedbackFactory> */
    use HasFactory , HasUuids;

    protected $fillable = [
      'clinic_id',
      'description'
    ];
}
