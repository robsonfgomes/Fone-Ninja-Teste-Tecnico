<?php

namespace App\Http\Resources\Sale;

use App\Models\Sale\Sale;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Sale $this */
        return [
            'id'           => $this->id,
            'customerName' => $this->customer_name,
            'status'       => $this->status->value,
            'totalAmount'  => $this->totalAmount,
            'profit'       => $this->profit,
            'createdAt'    => $this->getCreatedAt(),
            'updatedAt'    => $this->getUpdatedAt(),
        ];
    }
}
