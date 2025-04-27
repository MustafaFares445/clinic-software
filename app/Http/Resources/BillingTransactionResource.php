<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="BillingTransactionResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="type", type="string", example="payment"),
 *     @OA\Property(property="amount", type="number", format="float", example=100.50),
 *     @OA\Property(property="description", type="string", example="Monthly subscription"),
 *     @OA\Property(property="user", ref="#/components/schemas/UserResource"),
 *     @OA\Property(property="createdAt", type="string", format="date-time", example="2023-01-01 12:00:00"),
 *     @OA\Property(property="updatedAt", type="string", format="date-time", example="2023-01-01 12:00:00"),
 *     @OA\Property(property="reservation", ref="#/components/schemas/ReservationResource"),
 *     @OA\Property(property="patient", ref="#/components/schemas/PatientResource"),
 * )
 */
class BillingTransactionResource extends JsonResource
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
            'type' => $this->when($this->type , $this->type),
            'amount' => $this->when($this->amount , $this->amount),
            'description' => $this->when($this->description , $this->description),
            'user' => UserResource::make($this->whenLoaded('user')),
            'createdAt' => $this->when($this->created_at, $this->created_at?->toDateTimeString()),
            'updatedAt' => $this->when($this->updated_at, $this->updated_at?->toDateTimeString()),
            'reservation' => ReservationResource::make($this->whenLoaded('reservation')),
            'patient' => PatientResource::make($this->whenLoaded('patient')),
        ];
    }
}