<?php

namespace App\Actions\PurchaseOrder;

use App\Dtos\PurchaseOrder\CreatePurchaseOrderDto;
use App\Models\Product\Product;
use App\Models\PurchaseOrder\PurchaseOrder;
use App\Models\PurchaseOrder\PurchaseOrderItem;
use Illuminate\Support\Facades\DB;
use App\Dtos\PurchaseOrder\PurchaseOrderItemDto;

class CreatePurchaseOrderAction
{
    public function execute(CreatePurchaseOrderDto $dto): PurchaseOrder
    {
        return DB::transaction(function () use ($dto) {
            $order = PurchaseOrder::create(['supplier_name' => $dto->supplier]);

            /** @var PurchaseOrderItemDto $item */
            foreach ($dto->items as $item) {
                /** @var Product $product */
                $product = Product::findOrFail($item->productId);

                PurchaseOrderItem::create([
                    'purchase_order_id' => $order->id,
                    'product_id'        => $item->productId,
                    'quantity'          => $item->quantity,
                    'unit_price'        => $item->unitPrice,
                ]);

                $product->updateAverageCost($item->quantity, $item->unitPrice);
                $product->incrementStock($item->quantity);
            }

            return $order->load('items');
        });
    }
}
