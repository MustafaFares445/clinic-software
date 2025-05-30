<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="FillingMaterialResource",
 *     type="object",
 *     title="Filling Material Resource",
 *     description="Filling Material resource representation",
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the filling material"
 *     ),
 *     @OA\Property(
 *         property="color",
 *         type="string",
 *         description="The color of the filling material"
 *     ),
 *     @OA\Property(
 *         property="laboratory",
 *         ref="#/components/schemas/LaboratoryResource",
 *         description="The laboratory associated with the filling material"
 *     )
 * )
 */
class FillingMaterialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'color' => $this->color,
            'laboratory' => LaboratoryResource::make($this->whenLoaded('laboratory'))
        ];
    }
}