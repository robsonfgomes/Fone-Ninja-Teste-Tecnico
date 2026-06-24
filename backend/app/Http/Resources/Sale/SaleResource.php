<?php

namespace App\Http\Resources\Sale;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Dtos\Sale\SaleResultDto;

class SaleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var SaleResultDto $this */
        return [
            'saleId'      => $this->sale->id,
            'totalAmount' => $this->totalAmount,
            'profit'      => $this->profit,
            'createdAt'   => $this->sale->getCreatedAt(),
            'updatedAt'   => $this->sale->getUpdatedAt(),
        ];
    }
}
