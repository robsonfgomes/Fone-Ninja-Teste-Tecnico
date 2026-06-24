<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'sellingPrice'  => (float) $this->selling_price,
            'currentStock'  => $this->current_stock,
            'averageCost'   => $this->average_cost !== null ? (float) $this->average_cost : null,
            'createdAt'     => $this->getCreatedAt(),
            'updatedAt'     => $this->getUpdatedAt(),
        ];
    }
}
