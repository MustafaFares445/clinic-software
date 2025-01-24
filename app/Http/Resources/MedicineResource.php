<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="MedicineResource",
 *     type="object",
 *     title="Medicine Resource",
 *     description="Medicine resource representation",
 *     @OA\Property(
 *         property="id",
 *         type="string",
 *         description="Unique identifier for the medicine",
 *         example="12343"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="name of the medicine",
 *         example="cetamol"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="description of the medicine",
 *         example="This is a sample medicine description."
 *     ),
 *     @OA\Property(
 *         property="specifications",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/SpecificationResource"),
 *         description="List of specification associated with the medicine"
 *     ),
 *     @OA\Property(
 *         property="image",
 *         ref="#/components/schemas/MediaResource",
 *         description="Medicine Image"
 *     )
 * )
 */
class MedicineResource extends JsonResource
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
            'name' => $this->when($this->name , $this->name),
            'specifications' => SpecificationResource::collection($this->whenLoaded('specifications')),
            'image' => $this->getFirstMedia('medicines'),
            'note' => $this->whenPivotLoaded('medicine_record' , $this->pivot->note)
        ];
    }
}
