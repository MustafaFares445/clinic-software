<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ClinicWorkingDayResource",
 *     title="Clinic Working Day Resource",
 *     description="Clinic working day resource",
 *     @OA\Property(property="id", type="integer", example=1, nullable=true, description="The working day ID"),
 *     @OA\Property(property="day", type="string", example="Monday", nullable=true, description="The day of the week"),
 *     @OA\Property(property="start", type="string", example="09:00", nullable=true, description="Start time of working hours"),
 *     @OA\Property(property="end", type="string", example="17:00", nullable=true, description="End time of working hours")
 * )
 */
class ClinicWorkingDayResource extends JsonResource
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
            'day' => $this->when($this->day , $this->day),
            'start' => $this->when($this->start , $this->start),
            'end' => $this->when($this->end , $this->end),
        ];
    }
}
