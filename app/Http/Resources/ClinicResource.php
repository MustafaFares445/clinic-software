<?php

namespace App\Http\Resources;

use App\Models\ClinicWorkingDay;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ClinicResource",
 *     type="object",
 *     title="Clinic Resource",
 *     description="Clinic resource representation",
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Name of the clinic"
 *     ),
 *     @OA\Property(
 *         property="address",
 *         type="string",
 *         description="Address of the clinic"
 *     ),
 *     @OA\Property(
 *         property="longitude",
 *         type="number",
 *         format="float",
 *         description="Longitude coordinate of the clinic"
 *     ),
 *     @OA\Property(
 *         property="latitude",
 *         type="number",
 *         format="float",
 *         description="Latitude coordinate of the clinic"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Description of the clinic"
 *     ),
 *     @OA\Property(
 *         property="isBanned",
 *         type="boolean",
 *         description="Indicates if the clinic is banned"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="Type of the clinic"
 *     ),
 *     @OA\Property(
 *         property="workingDays",
 *         type="array",
 *         description="Working days and hours of the clinic",
 *         @OA\Items(ref="#/components/schemas/ClinicWorkingDayResource")
 *     )
 * )
 */
final class ClinicResource extends JsonResource
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
            'address' => $this->address,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'description' => $this->description,
            'isBanned' => $this->isBanned,
            'type' => $this->type,
            'workingDays' => ClinicWorkingDayResource::collection($this->whenLoaded('workingDays'))
        ];
    }
}
