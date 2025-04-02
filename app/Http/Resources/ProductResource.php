<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'base_price'     => $this->base_price,
            'final_price'    => $this->bestPrice?->price ?? $this->base_price,
            'currency'       => $this->bestPrice?->currency_code ?? 'USD',
            'valid_from'     => $this->bestPrice?->start_date,
            'valid_to'       => $this->bestPrice?->end_date,
        ];
    }
}
