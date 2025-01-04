<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="SpecificationResource",
 *     description="Specification resource representation",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Unique identifier for the specification",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Name of the specification",
 *         example="Specification Name"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Description of the specification",
 *         example="This is a detailed description of the specification."
 *     ),
 *     @OA\Property(
 *         property="image",
 *         ref="#/components/schemas/MediaResource",
 *         description="Media resource associated with the specification"
 *     )
 * )
 */
class SpecificationResource extends JsonResource
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
            'image' => MediaResource::make($this->getFirstMedia('specifications'))
        ];
    }
}
