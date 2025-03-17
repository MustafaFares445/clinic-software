<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="DoctorResource",
 *     type="object",
 *     title="Doctor Resource",
 *     description="Doctor resource representation",
 *
 *     @OA\Property(
 *         property="string",
 *         type="string",
 *         description="The unique identifier of the doctor"
 *     ),
 *     @OA\Property(
 *         property="fullName",
 *         type="string",
 *         description="The full name of the doctor"
 *     ),
 *     @OA\Property(
 *         property="avatar",
 *         ref="#/components/schemas/MediaResource",
 *         description="The avatar of the doctor"
 *     )
 * )
 */
final class DoctorResource extends JsonResource
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
            'firstName' => $this->when($this->firstName, $this->firstName),
            'lastName' => $this->when($this->lastName, $this->lastName),
            'avatar' => MediaResource::make($this->getFirstMedia('users')),
        ];
    }
}
