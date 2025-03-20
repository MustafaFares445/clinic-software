<?php

namespace App\Http\Resources;

use App\Models\MedicalTransactions;
use App\Models\Reservation;
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
 *     @OA\Property(property="user_id", type="integer", example=123),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01 12:00:00"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01 12:00:00"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example="2023-01-01 12:00:00")
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
        $data =  [
            'id' => $this->when($this->id , $this->id),
            'type' => $this->when($this->type , $this->type),
            'amount' => $this->when($this->amount , $this->amount),
            'description' => $this->when($this->description , $this->description),
            'user' => UserResource::make($this->whenLoaded('user')),
            // 'createdAt' => $this->created_at,
            // 'updatedAt' => $this->when($this->updated_at, $this->updated_at->toDateTimeString()),
        ];

        if($this->model_type === Reservation::class)
            $data['reservation'] = ReservationResource::make($this->model);

        if($this->model_type === MedicalTransactions::class)
            $data['medicalTransaction'] = MedicalTransactionResource::make($this->model);

        return $data;
    }
}