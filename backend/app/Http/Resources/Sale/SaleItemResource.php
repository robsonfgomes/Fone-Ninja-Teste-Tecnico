<?php

namespace App\Http\Resources\Sale;

use App\Models\Sale\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Product\ProductResource;

class SaleItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var SaleItem $this */
        return [
            'id'            => $this->id,
            'quantity'      => $this->quantity,
            'unitPrice'     => (float) $this->unit_price,
            'totalAmount'  => $this->totalAmount,
            'profit'       => $this->profit,
            'createdAt'     => $this->getCreatedAt(),
            'updatedAt'     => $this->getUpdatedAt(),
            'product'       => ProductResource::make($this->product),
        ];
    }
}
