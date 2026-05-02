<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AvailableSlotsResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'start_at' => $this['start'] ?? null,
            'end_at' => $this['end'] ?? null,
        ];
    }
}
