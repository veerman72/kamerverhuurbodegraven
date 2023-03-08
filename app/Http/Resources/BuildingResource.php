<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BuildingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //        return parent::toArray($request);
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'details' => BuildingDetailsResource::make($this),
            'owner' => OwnerResource::make($this->whenLoaded('owner')),
            'units' => UnitResource::collection($this->whenLoaded('units')),
        ];
    }
}
