<?php

namespace App\Actions\Sale;

use App\Dtos\Sale\SaleItemDto;
use App\Models\Product\Product;

class CalculateSaleTotalsAction
{
    /** @param SaleItemDto[] $items */
    public function execute(array $items): array
    {
        $totalAmount = 0;
        $totalProfit = 0;

        foreach ($items as $item) {
            $product = Product::findOrFail($item->productId);

            $totalAmount += $item->unitPrice * $item->quantity;
            $totalProfit += ($item->unitPrice - (float) ($product->average_cost ?? 0)) * $item->quantity;
        }

        return [
            'totalAmount' => round($totalAmount, 2),
            'profit'      => round($totalProfit, 2),
        ];
    }
}
