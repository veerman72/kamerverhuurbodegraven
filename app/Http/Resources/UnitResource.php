<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
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
            'category' => $this->category
                ->get()
                ->except('id')
                ->toArray(),
            'status' => $this->status
                ->get()
                ->except('id')
                ->toArray(),
            'details' => UnitDetailsResource::make($this),
            'services' => ServicesResource::make($this),
            'energy' => EnergyResource::make($this),
            'building' => BuildingResource::make($this->whenLoaded('building')),
            'exceptional_provisions' => ContractualExceptionalProvisionsResource::collection(
                $this->whenLoaded('contract_provisions'),
            ),
        ];
    }
}
