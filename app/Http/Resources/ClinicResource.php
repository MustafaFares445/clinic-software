<?php

namespace App\Http\Resources;

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
 *         property="start",
 *         type="string",
 *         format="date-time",
 *         description="Opening time of the clinic"
 *     ),
 *     @OA\Property(
 *         property="end",
 *         type="string",
 *         format="date-time",
 *         description="Closing time of the clinic"
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
            'start' => $this->start,
            'end' => $this->end,
        ];
    }
}
