<?php

namespace App\Models\PurchaseOrder;

use App\Models\Abstract\AbstractModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;

/**
 * @property string $id
 * @property string $purchase_order_id
 * @property string $product_id
 * @property int $quantity
 * @property string $unit_price
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
#[Fillable(['purchase_order_id', 'product_id', 'quantity', 'unit_price'])]
class PurchaseOrderItem extends AbstractModel
{
    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'quantity'   => 'integer',
        ];
    }
}
