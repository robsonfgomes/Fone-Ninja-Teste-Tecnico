<?php

namespace App\Models\Sale;

use App\Enums\SaleStatusEnum;
use App\Models\Abstract\AbstractModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $customer_name
 * @property SaleStatusEnum $status
 * @property float $totalAmount
 * @property float $profit
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
#[Fillable(['customer_name', 'status'])]
class Sale extends AbstractModel
{
    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    protected function totalAmount(): Attribute
    {
        return Attribute::get(
            fn() => round(
                $this->items->sum(
                    fn($item) => (float) $item->unit_price * $item->quantity
                ),
                2
            )
        );
    }

    protected function profit(): Attribute
    {
        return Attribute::get(
            fn() => round(
                $this->items->sum(
                    fn($item) => ((float) $item->unit_price - (float) ($item->product?->average_cost ?? 0)) * $item->quantity
                ),
                2
            )
        );
    }

    protected function casts(): array
    {
        return [
            'status' => SaleStatusEnum::class,
        ];
    }
}
