<?php

namespace App\Actions\Sale;

use App\Actions\Product\UpdateProductStockAction;
use App\Enums\SaleStatusEnum;
use App\Exceptions\SaleAlreadyCancelledException;
use App\Models\Sale\Sale;
use App\Models\Sale\SaleItem;
use Illuminate\Support\Facades\DB;

class CancelSaleAction
{
    public function __construct(
        private readonly UpdateProductStockAction $updateProductStockAction,
    ) {}

    public function execute(Sale $sale): Sale
    {
        if ($sale->status === SaleStatusEnum::Cancelled) {
            throw new SaleAlreadyCancelledException();
        }

        $sale->load('items.product');

        return DB::transaction(function () use ($sale) {
            $sale->update(['status' => SaleStatusEnum::Cancelled]);

            /** @var SaleItem $item */
            foreach ($sale->items as $item) {
                $this->updateProductStockAction->execute($item->product, $item->quantity);
            }

            return $sale->refresh();
        });
    }
}
