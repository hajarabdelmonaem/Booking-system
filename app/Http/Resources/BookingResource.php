<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date->format('Y-m-d'),
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'price' => $this->price,
            'status' => $this->status,
            'service' => new ServiceResource($this->whenLoaded('service')),
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
        ];
    }
}
