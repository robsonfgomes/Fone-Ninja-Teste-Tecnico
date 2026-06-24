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
        $totalAmount = $this->items->reduce(
            fn (string $carry, $item) => bcadd(
                $carry,
                bcmul((string) $item->quantity, $item->unit_price, 4),
                4
            ),
            '0'
        );

        return [
            'purchaseOrderId' => $this->id,
            'totalAmount'     => (float) bcdiv($totalAmount, '1', 2),
            'createdAt'       => $this->getCreatedAt(),
            'updatedAt'       => $this->getUpdatedAt(),
        ];
    }
}
