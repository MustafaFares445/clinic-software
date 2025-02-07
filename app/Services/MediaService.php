<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class MediaService
{
    public array $mediaCollections;

    public function __construct(array $mediaCollections = ['files' , 'images' , 'audios' , 'videos'])
    {
        $this->mediaCollections = $mediaCollections;
    }

    public function handleMediaUpload(Model $model , Request $request): void
    {
        foreach ($this->mediaCollections as $collection) {
            if ($request->hasFile($collection)) {
                foreach ($request->file($collection) as $file)
                    $model->addMedia($file)->toMediaCollection($collection);
            }
        }
    }
}
