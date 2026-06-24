<?php

namespace App\Http\Resources\PurchaseOrder;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderResource extends JsonResource
{
    public function jsonOptions(): int
    {
        return JSON_PRESERVE_ZERO_FRACTION;
    }

    public function toArray(Request $request): array
    {
        $totalAmount = $this->items->sum(
            fn ($item) => $item->quantity * (float) $item->unit_price
        );

        return [
            'purchaseOrderId' => $this->id,
            'totalAmount'     => (float) round($totalAmount, 2),
            'createdAt'       => $this->getCreatedAt(),
            'updatedAt'       => $this->getUpdatedAt(),
        ];
    }
}
