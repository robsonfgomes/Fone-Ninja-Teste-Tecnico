<?php

namespace App\Models\Sale;

use App\Models\Abstract\AbstractModel;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property string $id
 * @property string $sale_id
 * @property string $product_id
 * @property int $quantity
 * @property string $unit_price
 * @property float $totalAmount
 * @property float $profit
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

    protected function totalAmount(): Attribute
    {
        return Attribute::get(
            fn() => round((float) $this->unit_price * $this->quantity, 2)
        );
    }

    protected function profit(): Attribute
    {
        return Attribute::get(
            fn() => round(
                ((float) $this->unit_price - (float) ($this->product?->average_cost ?? 0)) * $this->quantity,
                2
            )
        );
    }

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'quantity'   => 'integer',
        ];
    }
}
