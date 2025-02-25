<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="IllResource",
 *     type="object",
 *     title="Ill Resource",
 *     description="Ill resource representation",
 *
 *     @OA\Property(
 *         property="id",
 *         type="string",
 *         description="Unique identifier for the ill",
 *         example="1245654"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Name of the ill",
 *         example="ill-name"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Description of the ill",
 *         example="This is a sample ill description."
 *     ),
 *     @OA\Property(
 *         property="specifications",
 *         type="array",
 *
 *         @OA\Items(ref="#/components/schemas/SpecificationResource"),
 *         description="List of specifications associated with the ill"
 *     ),
 *
 *     @OA\Property(
 *         property="image",
 *         ref="#/components/schemas/MediaResource",
 *         description="Image associated with the ill"
 *     )
 * )
 */
final class IllResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->when($this->id, $this->id),
            'name' => $this->when($this->name, $this->name),
            'description' => $this->when($this->description, $this->description),
            'specifications' => SpecificationResource::collection($this->whenLoaded('specifications')),
            'recordsCount' => $this->when($this->records_count , $this->records_count)
        ];
    }
}
