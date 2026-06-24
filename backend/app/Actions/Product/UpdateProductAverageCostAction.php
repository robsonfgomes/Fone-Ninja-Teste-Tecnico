<?php

namespace App\Actions\Product;

use App\Models\Product\Product;

class UpdateProductAverageCostAction
{
    public function execute(Product $product, int $quantity, string $unitPrice): void
    {
        $currentStockValue = $product->current_stock * (float) ($product->average_cost ?? 0);
        $incomingStockValue = $quantity * (float) $unitPrice;
        $totalQuantity = $product->current_stock + $quantity;

        $newAverageCost = round(($currentStockValue + $incomingStockValue) / $totalQuantity, 2);

        $product->update(['average_cost' => $newAverageCost]);
    }
}
