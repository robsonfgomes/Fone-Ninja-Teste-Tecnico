<?php

namespace App\Dtos\Sale;

class SaleListItemDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $customerName,
        public readonly string $status,
        public readonly float $totalAmount,
        public readonly float $profit,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    ) {}
}
