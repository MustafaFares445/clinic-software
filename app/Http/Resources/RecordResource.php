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
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="The unique identifier of the record"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="The type of the record"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="The description of the record"
 *     ),
 *     @OA\Property(
 *         property="tooth",
 *         ref="#/components/schemas/ToothResource",
 *         description="The tooth associated with the record"
 *     ),
 *     @OA\Property(
 *         property="treatment",
 *         ref="#/components/schemas/TreatmentResource",
 *         description="The treatment associated with the record"
 *     ),
 *     @OA\Property(
 *         property="fillingMaterial",
 *         ref="#/components/schemas/FillingMaterialResource",
 *         description="The filling material associated with the record"
 *     ),
 *     @OA\Property(
 *         property="medicalSession",
 *         ref="#/components/schemas/MedicalSessionResource",
 *         description="The medical session associated with the record"
 *     ),
 *     @OA\Property(
 *         property="doctors",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/UserResource"),
 *         description="The doctors associated with the record"
 *     ),
 *     @OA\Property(
 *         property="createdAt",
 *         type="string",
 *         format="date",
 *         description="The creation date of the record"
 *     ),
 *     @OA\Property(
 *         property="updatedAt",
 *         type="string",
 *         format="date",
 *         description="The last update date of the record"
 *     )
 * )
 */
final class RecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'description' => $this->description,
            'tooth' => ToothResource::make($this->whenLoaded('tooth')),
            'treatment' => TreatmentResource::make($this->whenLoaded('treatment')),
            'fillingMaterial' => FillingMaterialResource::make($this->whenLoaded('fillingMaterial')),
            'doctors' => UserResource::collection($this->whenLoaded('doctors')),
            'createdAt' => $this->created_at?->toDateString(),
            'updatedAt' => $this->updated_at?->toDateString(),
        ];
    }
}
