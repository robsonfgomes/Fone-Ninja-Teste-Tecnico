<?php

namespace App\Http\Resources\Sale;

use App\Dtos\Sale\SaleListItemDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListSaleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var SaleListItemDto $this */
        return [
            'id'           => $this->id,
            'customerName' => $this->customerName,
            'status'       => $this->status,
            'totalAmount'  => $this->totalAmount,
            'profit'       => $this->profit,
            'createdAt'    => $this->createdAt,
            'updatedAt'    => $this->updatedAt,
        ];
    }
}
