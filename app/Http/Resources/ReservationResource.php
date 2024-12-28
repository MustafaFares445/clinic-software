<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Schema(
 *     schema="ReservationResource",
 *     type="object",
 *     title="Reservation Resource",
 *     description="Reservation resource representation",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Reservation ID"
 *     ),
 *     @OA\Property(
 *         property="start",
 *         type="string",
 *         format="date-time",
 *         description="Start date and time of the reservation"
 *     ),
 *     @OA\Property(
 *         property="end",
 *         type="string",
 *         format="date-time",
 *         description="End date and time of the reservation"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="Type of the reservation"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         description="Status of the reservation"
 *     ),
 *     @OA\Property(
 *         property="patient",
 *         ref="#/components/schemas/PatientResource",
 *         description="Patient associated with the reservation"
 *     ),
 *     @OA\Property(
 *         property="createdAt",
 *         type="string",
 *         format="date-time",
 *         description="Creation timestamp of the reservation"
 *     )
 * )
 */
class ReservationResource extends JsonResource
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
           'start' => Carbon::parse($this->start)->toDateTimeString(),
            'end' => Carbon::parse($this->end)->toDateTimeString(),
            'type' => $this->type,
            'status' => $this->status,
            'patient' => PatientResource::make($this->whenLoaded('patient')),
            'createdAt' => Carbon::parse($this->created_at)->toDateTimeString()
        ];
    }
}
