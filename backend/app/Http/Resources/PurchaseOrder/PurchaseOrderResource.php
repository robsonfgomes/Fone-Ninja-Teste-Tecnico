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
        return [
            'id'              => $this->id,
            'supplierName'    => $this->supplier_name,
            'totalAmount'     => $this->totalAmount,
            'createdAt'       => $this->getCreatedAt(),
            'updatedAt'       => $this->getUpdatedAt(),
        ];
    }
}
