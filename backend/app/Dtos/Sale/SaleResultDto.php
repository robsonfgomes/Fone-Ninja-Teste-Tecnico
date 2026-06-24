<?php

namespace App\Dtos\Sale;

use App\Models\Sale\Sale;

class SaleResultDto
{
    public function __construct(
        public readonly Sale $sale,
        public readonly float $totalAmount,
        public readonly float $profit,
    ) {}
}
