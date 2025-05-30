<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="LaboratoryResource",
 *     type="object",
 *     title="Laboratory Resource",
 *     description="Laboratory resource representation",
 *     @OA\Property(
 *         property="id",
 *         type="string",
 *         description="The ID of the laboratory"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the laboratory"
 *     ),
 *     @OA\Property(
 *         property="address",
 *         type="string",
 *         description="The address of the laboratory"
 *     ),
 *     @OA\Property(
 *         property="phone",
 *         type="string",
 *         description="The phone number of the laboratory"
 *     ),
 *     @OA\Property(
 *         property="whatsapp",
 *         type="string",
 *         description="The WhatsApp number of the laboratory"
 *     ),
 *     @OA\Property(
 *         property="fillingMaterials",
 *         type="array",
 *         description="List of filling materials associated with the laboratory",
 *         @OA\Items(ref="#/components/schemas/FillingMaterialResource")
 *     )
 * )
 */
class LaboratoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone,
            'whatsapp' => $this->whatsapp,
            'fillingMaterials' => FillingMaterialResource::collection($this->whenLoaded('fillingMaterials')),
        ];
    }
}