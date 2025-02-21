<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaService
{
    public static array $mediaCollections = ['files' , 'images' , 'audios' , 'videos'];
    public static array $medicalMediaCollections = [
        'x-ray',
        'tests',
        'mri-scans',
        'ct-scans',
        'ultrasound',
        'lab-reports',
        'medical-reports',
        'patient-history',
        'prescriptions',
        'ecg-records',
    ];

    public function handleMultipleMediaUpload(Model $model , Request $request): void
    {
        collect(array_merge(self::$mediaCollections, self::$mediaCollections))->map(function (string $collection) use ($model , $request){
            foreach ($request->file($collection) as $file)
                $this->handleMediaUpload($model , $file , $collection);
        });
    }

    public function handleMediaUpload(Model $model, $media, string $collection): Media|null
    {
        if ($media)
            return $model->addMedia($media)->usingName($media->hashName())->toMediaCollection($collection);

        return null;
    }

    public function handleMediaUploadByPath(Model $model,string $path, string $collection): Media|null
    {
        if ($media)
            return $model->addMedia($media)->usingName($media->hashName())->toMediaCollection($collection);

        return null;
    }
}
