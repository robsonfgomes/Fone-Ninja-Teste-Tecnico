<?php

namespace App\Actions\Sale;

use App\Dtos\Sale\CreateSaleDto;
use App\Dtos\Sale\SaleItemDto;
use App\Enums\SaleStatusEnum;
use App\Models\Product\Product;
use App\Models\Sale\Sale;
use App\Models\Sale\SaleItem;
use Illuminate\Support\Facades\DB;

class CreateSaleAction
{
    public function execute(CreateSaleDto $dto): Sale
    {
        $sale = DB::transaction(function () use ($dto) {
            $sale = Sale::create([
                'customer_name' => $dto->customer,
                'status' => SaleStatusEnum::Active
            ]);

            /** @var SaleItemDto $saleItemDto */
            foreach ($dto->items as $saleItemDto) {
                /** @var Product $product */
                $product = Product::findOrFail($saleItemDto->productId);

                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $saleItemDto->productId,
                    'quantity'   => $saleItemDto->quantity,
                    'unit_price' => $saleItemDto->unitPrice,
                ]);

                $product->decrementStock($saleItemDto->quantity);
            }

            return $sale;
        });

        $sale->load('items.product');

        return $sale;
    }
}
