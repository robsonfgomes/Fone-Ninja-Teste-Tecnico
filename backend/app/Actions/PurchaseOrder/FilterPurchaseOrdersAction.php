<?php

namespace App\Actions\PurchaseOrder;

use App\Dtos\PurchaseOrder\FilterPurchaseOrdersDto;
use App\Models\PurchaseOrder\PurchaseOrder;
use Illuminate\Pagination\LengthAwarePaginator;

class FilterPurchaseOrdersAction
{
    public function execute(FilterPurchaseOrdersDto $dto): LengthAwarePaginator
    {
        return PurchaseOrder::query()
            ->with('items')
            ->orderByDesc('created_at')
            ->paginate(
                perPage: $dto->perPage,
                page: $dto->page
            );
    }
}
