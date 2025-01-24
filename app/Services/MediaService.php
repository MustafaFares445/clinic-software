<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class MediaService
{
    public function handleMediaUpload(Model $model , Request $request , array $mediaCollections = ['files' , 'images' , 'audios' , 'videos']): void
    {
        foreach ($mediaCollections as $collection) {
            if ($request->hasFile($collection)) {
                foreach ($request->file($collection) as $file)
                    $model->addMedia($file)->toMediaCollection($collection);
            }
        }
    }
}
