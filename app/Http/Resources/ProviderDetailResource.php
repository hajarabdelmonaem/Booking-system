<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Provider */
class ProviderDetailResource extends JsonResource
{
    
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->whenLoaded('user', fn () => $this->user->name),
            'services_count' => $this->whenCounted('services'),
            'bio' => $this->bio,
            'experience_years' => $this->experience_years,
            'availabilities' => ProviderAvailabilitiesResource::collection($this->whenLoaded('availabilities')),
        ];
    }
}
