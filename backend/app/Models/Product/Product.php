<?php

namespace App\Models\Product;

use App\Models\Abstract\AbstractModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;

/**
 * @property string $id
 * @property string $name
 * @property string $selling_price
 * @property int $current_stock
 * @property string|null $average_cost
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
#[Fillable(['name', 'selling_price', 'current_stock', 'average_cost'])]
class Product extends AbstractModel
{
    public function incrementStock(int $quantity): void
    {
        $this->update(['current_stock' => $this->current_stock + $quantity]);
    }

    public function decrementStock(int $quantity): void
    {
        $this->update(['current_stock' => $this->current_stock - $quantity]);
    }

    protected function casts(): array
    {
        return [
            'selling_price' => 'decimal:2',
            'average_cost'  => 'decimal:2',
            'current_stock' => 'integer',
        ];
    }
}
