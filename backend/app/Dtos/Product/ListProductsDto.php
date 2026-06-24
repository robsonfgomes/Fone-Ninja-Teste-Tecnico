<?php

namespace App\Dtos\Product;

use App\Interfaces\DtoInterface;

class ListProductsDto implements DtoInterface
{
    public function __construct(
        public readonly int $page,
        public readonly int $perPage,
    ) {}
}
