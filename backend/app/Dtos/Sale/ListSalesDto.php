<?php

namespace App\Dtos\Sale;

use App\Interfaces\DtoInterface;

class ListSalesDto implements DtoInterface
{
    public function __construct(
        public readonly int $page,
        public readonly int $perPage,
    ) {}
}
