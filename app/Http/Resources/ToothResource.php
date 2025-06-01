<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ToothResource",
 *     type="object",
 *     title="Tooth Resource",
 *     description="Tooth resource representation",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="The ID of the tooth"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the tooth"
 *     ),
 *     @OA\Property(
 *         property="number",
 *         type="integer",
 *         description="The number of the tooth"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="The type of the tooth"
 *     ),
 *     @OA\Property(
 *         property="records",
 *         type="array",
 *         description="The records associated with the tooth",
 *         @OA\Items(ref="#/components/schemas/RecordResource")
 *     )
 * )
 */
class ToothResource extends JsonResource
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
            'number' => $this->number,
            'type' => $this->type,
            'records' => RecordResource::collection($this->whenLoaded('records')),
        ];
    }
}
