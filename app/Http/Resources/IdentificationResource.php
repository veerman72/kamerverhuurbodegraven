<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IdentificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'document' => [
                ...$this->id_document_type
                    ->get()
                    ->except('id')
                    ->toArray(),
                ...['number' => $this->id_document_number],
            ],
            'social_number' => $this->social_number,
        ];
    }
}
