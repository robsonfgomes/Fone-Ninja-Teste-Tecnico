<?php

namespace App\Dtos\Sale;

class SaleItemDto
{
    public function __construct(
        public readonly string $productId,
        public readonly int $quantity,
        public readonly float $unitPrice,
    ) {}
}
