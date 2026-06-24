<?php

namespace App\Models\Sale;

use App\Enums\Sale\SaleStatus;
use App\Models\Abstract\AbstractModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $customer_name
 * @property SaleStatus $status
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

    protected function casts(): array
    {
        return [
            'status' => SaleStatus::class,
        ];
    }
}
