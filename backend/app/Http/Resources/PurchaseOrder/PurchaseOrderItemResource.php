<?php

namespace App\Http\Resources\PurchaseOrder;

use App\Models\PurchaseOrder\PurchaseOrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Product\ProductResource;

class PurchaseOrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var PurchaseOrderItem $this */
        return [
            'id'            => $this->id,
            'quantity'      => $this->quantity,
            'unitPrice'     => (float) $this->unit_price,
            'totalAmount'   => $this->totalAmount,
            'product'       => ProductResource::make($this->product),
            'createdAt'     => $this->getCreatedAt(),
            'updatedAt'     => $this->getUpdatedAt(),
        ];
    }
}
