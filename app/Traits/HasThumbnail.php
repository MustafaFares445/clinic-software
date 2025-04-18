<?php

namespace App\Traits;

use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasThumbnail
{
    use InteractsWithMedia;

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(200)  // Thumbnail width
            ->height(200) // Thumbnail height
            ->sharpen(10) // Optional: sharpen the image
            ->keepOriginalImageFormat();
    }
}
