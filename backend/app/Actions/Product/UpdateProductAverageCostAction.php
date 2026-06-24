<?php

namespace App\Actions\Product;

use App\Models\Product\Product;

class UpdateProductAverageCostAction
{
    public function execute(Product $product, int $quantity, string $unitPrice): void
    {
        $newAverageCost = (
            ($product->current_stock * (float) ($product->average_cost ?? 0))
            + ($quantity * (float) $unitPrice)
        ) / ($product->current_stock + $quantity);

        $product->update(['average_cost' => $newAverageCost]);
    }
}
