<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'description' => $this->description,
            'surface' => $this->surface,
            'price' => $this->price,
            'independent_living_space' => $this->independent_living_space,
            'shared_entrance' => $this->shared_entrance,
            'rooms' => $this->rooms,
            'furnished' => $this->furnished,
            'upholstered' => $this->upholstered,
            'published' => $this->published,
        ];
    }
}
