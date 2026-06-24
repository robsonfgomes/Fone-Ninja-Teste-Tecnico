<?php

namespace App\Http\Resources\Sale;

use App\Models\Sale\Sale;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CancelledSaleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Sale $this */
        return [
            'saleId'    => $this->id,
            'status'    => $this->status->value,
            'createdAt' => $this->getCreatedAt(),
            'updatedAt' => $this->getUpdatedAt(),
        ];
    }
}
