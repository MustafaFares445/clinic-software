<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="MediaResource",
 *     description="Media resource representation",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Unique identifier for the media",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Name of the media",
 *         example="Nature.jpg"
 *     ),
 *     @OA\Property(
 *         property="fileName",
 *         type="string",
 *         description="File name of the media",
 *         example="nature_image.jpg"
 *     ),
 *     @OA\Property(
 *         property="collection",
 *         type="string",
 *         description="Collection name of the media",
 *         example="Nature Collection"
 *     ),
 *     @OA\Property(
 *         property="url",
 *         type="string",
 *         description="Full URL of the media",
 *         example="https://example.com/media/nature.jpg"
 *     ),
 *     @OA\Property(
 *         property="size",
 *         type="string",
 *         description="Human-readable size of the media",
 *         example="2.5 MB"
 *     )
 * )
 */
class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'fileName' => $this->file_name,
            'collection' => $this->collection_name,
            'url' => $this->getFullUrl(),
            'size' => $this->human_readable_size,
        ];
    }
}
