<?php

namespace App\Actions\Product;

use App\Models\Product\Product;

class UpdateProductStockAction
{
    public function execute(Product $product, int $quantity): void
    {
        $product->update(['current_stock' => $product->current_stock + $quantity]);
    }
}
