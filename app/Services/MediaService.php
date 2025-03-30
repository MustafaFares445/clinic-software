<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Enums\MedicalMediaCollection;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

final class MediaService
{
    public static array $mediaCollections = ['files', 'images', 'audios', 'videos'];

    public function handleMediaUpload(Model $model, $media, MedicalMediaCollection $collection): ?Media
    {
        if ($media) {
            return $model->addMedia($media)->usingName($media->hashName())->toMediaCollection($collection);
        }

        return null;
    }
}
