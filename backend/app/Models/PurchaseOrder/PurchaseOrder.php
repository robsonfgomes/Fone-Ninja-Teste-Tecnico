<?php

namespace App\Models\PurchaseOrder;

use App\Models\Abstract\AbstractModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $supplier_name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
#[Fillable(['supplier_name'])]
class PurchaseOrder extends AbstractModel
{
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
