<?php

namespace App\Dtos\PurchaseOrder;

class PurchaseOrderItemDto
{
    public function __construct(
        public readonly string $productId,
        public readonly int $quantity,
        public readonly float $unitPrice,
    ) {}
}
