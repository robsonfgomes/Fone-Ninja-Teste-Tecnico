<?php

namespace App\Models\Sale;

use App\Models\Abstract\AbstractModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $customer_name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
#[Fillable(['customer_name'])]
class Sale extends AbstractModel
{
    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
}
