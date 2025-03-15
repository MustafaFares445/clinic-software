<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class TransactionResource extends JsonResource
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
            'type' => $this->when($this->type, $this->type),
            'amount' => $this->when($this->amount, $this->amount),
            'from' => $this->when($this->from, $this->from),
            'description' => $this->when($this->description, $this->description),
            'createdAt' => $this->when($this->created_at , $this->created_at->toDateTimeString()),
        ];
    }
}
