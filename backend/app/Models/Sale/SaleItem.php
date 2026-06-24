<?php

namespace App\Models\Sale;

use App\Models\Abstract\AbstractModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;

/**
 * @property string $id
 * @property string $sale_id
 * @property string $product_id
 * @property int $quantity
 * @property string $unit_price
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
#[Fillable(['sale_id', 'product_id', 'quantity', 'unit_price'])]
class SaleItem extends AbstractModel
{
    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'quantity'   => 'integer',
        ];
    }
}
