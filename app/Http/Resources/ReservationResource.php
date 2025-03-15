<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ReservationResource",
 *     type="object",
 *     title="Reservation Resource",
 *     description="Reservation resource representation",
 *
 *     @OA\Property(
 *         property="id",
 *         type="string",
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
 *         property="doctor",
 *         ref="#/components/schemas/DoctorResource",
 *         description="Doctor associated with the reservation"
 *     ),
 *     @OA\Property(
 *         property="specification",
 *         ref="#/components/schemas/SpecificationResource",
 *         description="Specification associated with the reservation"
 *     ),
 *     @OA\Property(
 *         property="createdAt",
 *         type="string",
 *         format="date-time",
 *         description="Creation timestamp of the reservation"
 *     ),
 *     @OA\Property(
 *         property="pastReservationsCount",
 *         type="integer",
 *         description="Count of past reservations"
 *     ),
 *     @OA\Property(
 *         property="upComingReservationCount",
 *         type="integer",
 *         description="Count of upcoming reservations"
 *     )
 * )
 */
final class ReservationResource extends JsonResource
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
            // 'start' => $this->when($this->start, $this->start->toDateTimeString()),
            // 'end' => $this->when($this->end, $this->end->toDateTimeString()),
            'type' => $this->when($this->type, $this->type),
            'status' => $this->when($this->status, $this->status),
            'patient' => PatientResource::make($this->whenLoaded('patient')),
            'doctor' => $this->when($this->doctor_id, DoctorResource::make($this->whenLoaded('doctor'))),
            'specification' => $this->when($this->specification_id, SpecificationResource::make($this->whenLoaded('specification'))),
            'createdAt' => $this->when($this->created_at , $this->created_at->toDateTimeString()),
            'pastReservationsCount' => $this->when($this->pastReservationsCount, $this->pastReservationsCount),
            'upComingReservationCount' => $this->when($this->upComingReservationCount, $this->upComingReservationCount),
        ];
    }
}
