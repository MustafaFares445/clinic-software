<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ChronicDiseasResource",
 *     type="object",
 *     title="Chronic Disease Resource",
 *     description="Chronic Disease resource representation",
 *     @OA\Property(
 *         property="id",
 *         type="string",
 *         format="uuid",
 *         description="The unique identifier of the chronic disease",
 *         example="550e8400-e29b-41d4-a716-446655440000",
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="The description of the chronic disease",
 *         example="Diabetes"
 *     )
 * )
 */
class ChronicDiseasResource extends JsonResource
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
            'description' => $this->description,
        ];
    }
}