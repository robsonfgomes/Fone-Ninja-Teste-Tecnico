<?php

namespace App\Dtos\PurchaseOrder;

use App\Interfaces\DtoInterface;

class CreatePurchaseOrderDto implements DtoInterface
{
    public function __construct(
        public readonly string $supplier,
        /** @var PurchaseOrderItemDto[] */
        public readonly array $items,
    ) {}
}
