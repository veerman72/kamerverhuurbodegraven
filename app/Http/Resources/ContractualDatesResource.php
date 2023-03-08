<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractualDatesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'document' => $this->document,
            'start' => $this->start->format('d-m-Y'),
            'end' => $this->end->format('d-m-Y'),
            'next_expiration' => $this->next_expiration->format('d-m-Y'),
        ];
    }
}
