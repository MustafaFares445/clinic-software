<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HandlesMedia
{
    /**
     * Handle media upload for single or multiple images
     *
     * @param UploadedFile|array<UploadedFile> $media The media file(s) to upload
     * @param Model $model The model to associate the media with
     * @param string $collection The media collection name (default: 'default')
     * @return Media|array<Media> Returns the created media object(s)
     */
    public function handleMediaUpload(UploadedFile|array $media, Model $model, string $collection = 'default'): Media|array
    {
        return is_array($media)
            ? array_map(fn($file) => $model->addMedia($file)->toMediaCollection($collection), $media)
            : $model->addMedia($media)->toMediaCollection($collection);
    }

    /**
     * Update media by clearing existing collection and uploading new files
     *
     * @param UploadedFile|array<UploadedFile> $media The media file(s) to upload
     * @param Model $model The model to associate the media with
     * @param string $collection The media collection name (default: 'default')
     * @return Media|array<Media> Returns the created media object(s)
     */
    public function handleMediaUpdate(UploadedFile|array $media, Model $model, string $collection = 'default'): Media|array
    {
        $model->clearMediaCollection($collection);

        return $this->handleMediaUpload($media, $model, $collection);
    }

    /**
     * Delete all media in a collection
     *
     * @param Model $model The model containing the media collection
     * @param string $collection The media collection name (default: 'default')
     * @return void
     */
    public function handleMediaCollectionDeletion(Model $model, string $collection = 'default'): void
    {
        $model->clearMediaCollection($collection);
    }

    /**
     * Delete a specific media item after verifying it belongs to the model
     *
     * @param Model $model The model to verify ownership against
     * @param Media $media The media item to delete
     * @return bool Returns true if deletion was successful, false if media doesn't belong to model
     */
    public function handleMediaDeletion(Model $model, Media $media): bool
    {
        if (!$media->model->is($model))
            return false;

        return $media->delete();
    }
}
