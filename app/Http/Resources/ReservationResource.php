<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'start' => Carbon::parse($this->start)->toDateString(),
            'end' => Carbon::parse($this->end)->toDateString(),
            'type' => $this->type,
            'status' => $this->status,
            'patient' => PatientResource::make($this->whenLoaded('patient')),
            'createdAt' => Carbon::parse($this->created_at)->toDateTimeString()
        ];
    }
}
