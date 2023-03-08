<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BuildingDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'address' => AddressResource::make($this),
            'description' => $this->description,
            'energy_label' => $this->energy_label,
            'year' => BuildingYearResource::make($this),
            'distance' => BuildingDistanceResource::make($this),
        ];
    }
}
