<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="MedicalSessionResource",
 *     type="object",
 *     title="Medical Session Resource",
 *     description="Medical Session Resource",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="The ID of the medical session"
 *     ),
 *     @OA\Property(
 *         property="date",
 *         type="string",
 *         format="date",
 *         description="The date of the medical session"
 *     ),
 *     @OA\Property(
 *         property="records",
 *         type="array",
 *         description="List of records associated with the medical session",
 *         @OA\Items(ref="#/components/schemas/RecordResource")
 *     )
 * )
 */
class MedicalSessionResource extends JsonResource
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
            'date' => $this->date->toDateString(),
            'records' => RecordResource::collection($this->whenLoaded('records'))
        ];
    }
}
