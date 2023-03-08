<?php

namespace App\Http\Resources;

use App\Traits\NameVariants;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NameVariantsResource extends JsonResource
{
    //    use NameVariants;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'abbreviation' => $this->abbreviation(),
            'authorized' => $this->authorizedName(),
            'initials' => $this->nameWithInitials(),
            'full' => $this->fullName(),
        ];
    }
}
