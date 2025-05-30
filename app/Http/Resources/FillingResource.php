<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FillingResource extends JsonResource
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
            'name' => $this->name,
            'clinic_id' => $this->clinic_id,
            'laboratory_id' => $this->laboratory_id,
            'price' => $this->price,
            'clinic' => $this->whenLoaded('clinic'),
            'laboratory' => $this->whenLoaded('laboratory'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}