<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnergyMeteringResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'electricity' => $this->metering_electricity,
            'gas' => $this->metering_gas,
            'water' => $this->metering_water,
        ];
    }
}
