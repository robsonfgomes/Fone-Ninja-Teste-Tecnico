<?php

namespace App\Actions\PurchaseOrder;

use App\Actions\Product\UpdateProductAverageCostAction;
use App\Dtos\PurchaseOrder\CreatePurchaseOrderDto;
use App\Models\Product\Product;
use App\Models\PurchaseOrder\PurchaseOrder;
use App\Models\PurchaseOrder\PurchaseOrderItem;
use Illuminate\Support\Facades\DB;

class CreatePurchaseOrderAction
{
    public function __construct(
        private readonly UpdateProductAverageCostAction $updateProductAverageCostAction,
    ) {}

    public function execute(CreatePurchaseOrderDto $dto): PurchaseOrder
    {
        return DB::transaction(function () use ($dto) {
            $order = PurchaseOrder::create(['supplier_name' => $dto->supplier]);

            foreach ($dto->items as $item) {
                $product = Product::findOrFail($item->productId);

                PurchaseOrderItem::create([
                    'purchase_order_id' => $order->id,
                    'product_id'        => $item->productId,
                    'quantity'          => $item->quantity,
                    'unit_price'        => $item->unitPrice,
                ]);

                $this->updateProductAverageCostAction->execute($product, $item->quantity, $item->unitPrice);
                $product->incrementStock($item->quantity);
            }

            return $order->load('items');
        });
    }
}
