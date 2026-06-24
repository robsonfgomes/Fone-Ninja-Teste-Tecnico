<?php

namespace App\Actions\Sale;

use App\Actions\Product\UpdateProductStockAction;
use App\Dtos\Sale\CreateSaleDto;
use App\Dtos\Sale\SaleResultDto;
use App\Models\Product\Product;
use App\Models\Sale\Sale;
use App\Models\Sale\SaleItem;
use Illuminate\Support\Facades\DB;

class CreateSaleAction
{
    public function __construct(
        private readonly CalculateSaleTotalsAction $calculateSaleTotalsAction,
        private readonly UpdateProductStockAction $updateProductStockAction,
    ) {}

    public function execute(CreateSaleDto $dto): SaleResultDto
    {
        ['totalAmount' => $totalAmount, 'profit' => $profit] = $this->calculateSaleTotalsAction->execute($dto->items);

        return DB::transaction(function () use ($dto, $totalAmount, $profit) {
            $sale = Sale::create(['customer_name' => $dto->customer]);

            foreach ($dto->items as $item) {
                $product = Product::findOrFail($item->productId);

                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $item->productId,
                    'quantity'   => $item->quantity,
                    'unit_price' => $item->unitPrice,
                ]);

                $this->updateProductStockAction->execute($product, -$item->quantity);
            }

            return new SaleResultDto(
                sale: $sale,
                totalAmount: $totalAmount,
                profit: $profit,
            );
        });
    }
}
