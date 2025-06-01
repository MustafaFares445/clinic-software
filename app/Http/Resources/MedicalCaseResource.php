<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="MedicalCaseResource",
 *     type="object",
 *     title="Medical Case Resource",
 *     description="Medical Case Resource",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="The ID of the medical case"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the medical case"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="The description of the medical case"
 *     ),
 *     @OA\Property(
 *         property="total",
 *         type="number",
 *         format="float",
 *         description="The total amount associated with the medical case"
 *     ),
 *     @OA\Property(
 *         property="date",
 *         type="string",
 *         format="date-time",
 *         description="The date of the medical case"
 *     ),
 *     @OA\Property(
 *         property="createdBy",
 *         ref="#/components/schemas/UserResource",
 *         description="The user who created the medical case"
 *     )
 * )
 */
class MedicalCaseResource extends JsonResource
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
            'name'=> $this->when($this->name, $this->name),
            'description' => $this->when($this->description, $this->description),
            'total'=>$this->when($this->total, $this->total),
            'date'=>$this->when($this->date, $this->date),
            'createdBy'=> UserResource::make($this->whenLoaded('createdBy')),
            'medicalSessions' => MedicalSessionResource::collection($this->whenLoaded('medicalSessions')),
        ];
    }
}