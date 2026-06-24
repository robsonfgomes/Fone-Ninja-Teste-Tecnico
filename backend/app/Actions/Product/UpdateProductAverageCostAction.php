<?php

namespace App\Actions\Product;

use App\Models\Product\Product;

class UpdateProductAverageCostAction
{
    public function execute(Product $product, int $quantity, string $unitPrice): void
    {
        $currentStock = (string) $product->current_stock;
        $currentAvgCost = $product->average_cost ?? '0';

        $numerator = bcadd(
            bcmul($currentStock, $currentAvgCost, 4),
            bcmul((string) $quantity, $unitPrice, 4),
            4
        );

        $denominator = (string) ($product->current_stock + $quantity);

        $newAverageCost = bcdiv($numerator, $denominator, 4);

        $product->update(['average_cost' => $newAverageCost]);
    }
}
