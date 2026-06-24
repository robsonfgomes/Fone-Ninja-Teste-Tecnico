<?php

namespace App\Dtos\Product;

use App\Interfaces\DtoInterface;

class CreateProductDto implements DtoInterface
{
    public function __construct(
        public readonly string $name,
        public readonly string $sellingPrice,
        public readonly int $initialStock,
    ) {}
}
