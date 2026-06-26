<?php

namespace App\Dtos\PurchaseOrder;

use App\Interfaces\DtoInterface;
use App\Dtos\PurchaseOrder\PurchaseOrderItemDto;

class CreatePurchaseOrderDto implements DtoInterface
{
    public function __construct(
        public readonly string $supplier,
        /** @var PurchaseOrderItemDto[] */
        public readonly array $items,
    ) {}
}
