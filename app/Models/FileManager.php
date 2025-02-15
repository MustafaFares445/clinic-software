<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class FileManager extends Model
{
    use InteractsWithMedia;

    protected $fillable = [
      'name'
    ];
}
