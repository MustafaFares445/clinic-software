<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="TreatmentResource",
 *     type="object",
 *     title="Treatment Resource",
 *     description="Treatment resource representation",
 *     @OA\Property(
 *         property="id",
 *         type="string",
 *         format="uuid",
 *         description="The unique identifier of the treatment"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the treatment"
 *     ),
 *     @OA\Property(
 *         property="color",
 *         type="string",
 *         description="The color associated with the treatment"
 *     )
 * )
 */
class TreatmentResource extends JsonResource
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
            'color' => $this->color,
        ];
    }
}
