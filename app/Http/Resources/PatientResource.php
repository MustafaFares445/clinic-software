<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="PatientResource",
 *     description="Patient resource representation",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Unique identifier for the patient",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="firstName",
 *         type="string",
 *         description="First name of the patient",
 *         example="John"
 *     ),
 *     @OA\Property(
 *         property="lastName",
 *         type="string",
 *         description="Last name of the patient",
 *         example="Doe"
 *     ),
 *     @OA\Property(
 *         property="avatar",
 *         ref="#/components/schemas/MediaResource",
 *         description="Avatar of the patient"
 *     ),
 *     @OA\Property(
 *         property="createdAt",
 *         type="string",
 *         format="date-time",
 *         description="Creation timestamp of the patient record",
 *         example="2023-10-12T07:20:50.52Z"
 *     )
 * )
 */
class PatientResource extends JsonResource
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
            'firstName' => $this->when($this->firstName , $this->firstName),
            'lastName' => $this->when($this->lastName , $this->lastName),
            'avatar' => MediaResource::make($this->getFirstMedia('patients')),
            'createdAt' => $this->when($this->created_at , Carbon::parse($this->created_at)->toDateTimeString())
        ];
    }
}
