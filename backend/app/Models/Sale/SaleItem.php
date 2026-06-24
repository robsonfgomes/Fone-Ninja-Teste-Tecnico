<?php

namespace App\Models\Sale;

use App\Models\Abstract\AbstractModel;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $sale_id
 * @property string $product_id
 * @property int $quantity
 * @property string $unit_price
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read Product $product
 */
#[Fillable(['sale_id', 'product_id', 'quantity', 'unit_price'])]
class SaleItem extends AbstractModel
{
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'quantity'   => 'integer',
        ];
    }
}
