<?php

namespace App\Dtos\Sale;

use App\Interfaces\DtoInterface;

class CreateSaleDto implements DtoInterface
{
    public function __construct(
        public readonly string $customer,
        /** @var SaleItemDto[] */
        public readonly array $items,
    ) {}
}
