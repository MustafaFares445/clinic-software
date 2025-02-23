<?php

namespace App\Http\Resources;

use App\Enums\RecordIllsTypes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="RecordResource",
 *     type="object",
 *     title="Record Resource",
 *     description="Record resource representation",
 *
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Unique identifier for the record",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Description of the record",
 *         example="This is a sample record description."
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="Type of the record",
 *         example="Medical"
 *     ),
 *     @OA\Property(
 *         property="date",
 *         type="string",
 *         format="date-time",
 *         description="Date of the record",
 *         example="2023-10-01T12:34:56Z"
 *     ),
 *     @OA\Property(
 *         property="reservation",
 *         ref="#/components/schemas/ReservationResource",
 *         description="Reservation associated with the record"
 *     ),
 *     @OA\Property(
 *         property="doctors",
 *         type="array",
 *
 *         @OA\Items(ref="#/components/schemas/DoctorResource"),
 *         description="List of doctors associated with the record"
 *     ),
 *
 *     @OA\Property(
 *         property="ills",
 *         type="array",
 *
 *         @OA\Items(ref="#/components/schemas/IllResource"),
 *         description="List of illnesses associated with the record"
 *     ),
 *
 *     @OA\Property(
 *         property="transientIlls",
 *         type="array",
 *
 *         @OA\Items(ref="#/components/schemas/IllResource"),
 *         description="List of transient illnesses associated with the record"
 *     ),
 *
 *     @OA\Property(
 *         property="medicines",
 *         type="array",
 *
 *         @OA\Items(ref="#/components/schemas/MedicineResource"),
 *         description="List of medicines associated with the record"
 *     ),
 *
 *     @OA\Property(
 *         property="transientMedicines",
 *         type="array",
 *
 *         @OA\Items(ref="#/components/schemas/MedicineResource"),
 *         description="List of transient medicines associated with the record"
 *     ),
 *
 *     @OA\Property(
 *         property="media",
 *         type="array",
 *
 *         @OA\Items(ref="#/components/schemas/MediaResource"),
 *         description="List of media files associated with the record"
 *     )
 * )
 */
final class RecordResource extends JsonResource
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
            'description' => $this->when($this->description, $this->description),
            'type' => $this->when($this->type, $this->type),
            'date' => $this->when($this->date, Carbon::parse($this->date)->toDateTimeString()),
            'reservation' => ReservationResource::make($this->whenLoaded('reservation')),
            'doctors' => DoctorResource::collection($this->whenLoaded('doctors')),
            'ills' => IllResource::collection($this->whenLoaded('ills', function () {
                return $this->ills->where('pivot.type', RecordIllsTypes::DIAGNOSED);
            })),
            'transientIlls' => IllResource::collection($this->whenLoaded('ills', function () {
                return $this->ills->where('pivot.type', RecordIllsTypes::TRANSIENT);
            })),
            'medicines' => MedicineResource::collection($this->whenLoaded('medicines', function () {
                return $this->medicines->where('pivot.type', RecordIllsTypes::DIAGNOSED);
            })),
            'transientMedicines' => MedicineResource::collection($this->whenLoaded('medicines', function () {
                return $this->medicines->where('pivot.type', RecordIllsTypes::TRANSIENT);
            })),
            'media' => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
