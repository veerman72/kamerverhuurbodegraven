<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource extends JsonResource
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
            'status' => $this->status
                ->get()
                ->except('id')
                ->toArray(),
            'details' => ContractualDetailsResource::make($this),
            'dates' => ContractualDatesResource::make($this),
            'services' => ServicesResource::make($this),
            'energy' => EnergyResource::make($this),
            'unit' => UnitResource::make($this->whenLoaded('unit')),
        ];
    }
}
