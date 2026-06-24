<?php

namespace App\Http\Resources\PurchaseOrder;

use App\Models\PurchaseOrder\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var PurchaseOrder $this */
        $totalAmount = $this->items->sum(
            fn($item) => $item->quantity * (float) $item->unit_price
        );

        return [
            'purchaseOrderId' => $this->id,
            'totalAmount'     => round($totalAmount, 2),
            'createdAt'       => $this->getCreatedAt(),
            'updatedAt'       => $this->getUpdatedAt(),
        ];
    }
}
