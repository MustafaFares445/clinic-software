<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="MedicalTransactionResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="quantity", type="integer", example=5),
 *     @OA\Property(property="type", type="string", example="medication"),
 *     @OA\Property(property="patient_id", type="integer", example=123),
 *     @OA\Property(property="clinic_id", type="integer", example=456),
 *     @OA\Property(property="description", type="string", example="Prescription for pain relief"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01 12:00:00"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01 12:00:00"),
 *     @OA\Property(
 *         property="doctor",
 *         ref="#/components/schemas/UserResource",
 *         nullable=true
 *     )
 * )
 */
class MedicalTransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'type' => $this->type,
            'description' => $this->description,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
            'doctor' => UserResource::make($this->whenLoaded('doctor')),
            'record' => RecordResource::make($this->whenLoaded('record'))
        ];
    }
}