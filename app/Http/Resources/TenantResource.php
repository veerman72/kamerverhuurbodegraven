<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TenantResource extends JsonResource
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
            'name' => NameResource::make($this),
            'birth' => BirthDataResource::make($this),
            'address' => AddressResource::make($this),
            'communication' => CommunicationResource::make($this),
            'identification' => IdentificationResource::make($this),
            'employer' => $this->employer,
            'contracts' => ContractResource::collection($this->whenLoaded('contracts')),
        ];
    }
}
