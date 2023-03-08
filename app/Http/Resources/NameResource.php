<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'last_name' => $this->last_name,
            'prefix' => $this->prefix,
            'first_name' => $this->first_name,
            'variants' => NameVariantsResource::make($this),
        ];
    }
}
