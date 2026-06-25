<?php

namespace App\Http\Resources\Sale;

use App\Models\Sale\Sale;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListSaleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Sale $this */
        $total  = $this->items->sum(fn($item) => $item->unit_price * $item->quantity);
        $profit = $this->items->sum(
            fn($item) => ($item->unit_price - (float) ($item->product->average_cost ?? 0)) * $item->quantity
        );

        return [
            'id'           => $this->id,
            'customerName' => $this->customer_name,
            'status'       => $this->status->value,
            'totalAmount'  => round((float) $total, 2),
            'profit'       => round((float) $profit, 2),
            'createdAt'    => $this->getCreatedAt(),
            'updatedAt'    => $this->getUpdatedAt(),
        ];
    }
}
