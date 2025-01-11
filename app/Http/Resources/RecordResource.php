<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="RecordResource",
 *     type="object",
 *     title="Record Resource",
 *     description="Record resource representation",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Unique identifier for the record",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Description of the record",
 *         example="This is a sample record description."
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="Type of the record",
 *         example="Medical"
 *     ),
 *     @OA\Property(
 *         property="images",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/MediaResource"),
 *         description="List of images associated with the record"
 *     ),
 *     @OA\Property(
 *         property="files",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/MediaResource"),
 *         description="List of files associated with the record"
 *     ),
 *     @OA\Property(
 *         property="reservation",
 *         ref="#/components/schemas/ReservationResource",
 *         description="Reservation associated with the record"
 *     )
 * )
 */
class RecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->when($this->id , $this->id),
            'description' => $this->when($this->description , $this->description),
            'type' => $this->when($this->type , $this->type),
            'images' => MediaResource::collection($this->whenLoaded('images')),
            'files' => MediaResource::collection($this->whenLoaded('files')),
            'reservation' => ReservationResource::make($this->whenLoaded('reservation'))
        ];
    }
}
