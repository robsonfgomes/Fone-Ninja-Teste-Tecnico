<?php

namespace App\Actions\Sale;

use App\Actions\Product\UpdateProductStockAction;
use App\Enums\Sale\SaleStatus;
use App\Exceptions\Sale\SaleAlreadyCancelledException;
use App\Models\Product\Product;
use App\Models\Sale\Sale;
use Illuminate\Support\Facades\DB;

class CancelSaleAction
{
    public function __construct(
        private readonly UpdateProductStockAction $updateProductStockAction,
    ) {}

    public function execute(Sale $sale): Sale
    {
        if ($sale->status === SaleStatus::Cancelled) {
            throw new SaleAlreadyCancelledException();
        }

        return DB::transaction(function () use ($sale) {
            $sale->update(['status' => SaleStatus::Cancelled]);

            foreach ($sale->items as $item) {
                $product = Product::findOrFail($item->product_id);
                $this->updateProductStockAction->execute($product, $item->quantity);
            }

            return $sale->refresh();
        });
    }
}
