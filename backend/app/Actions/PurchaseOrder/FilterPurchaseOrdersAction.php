<?php

namespace App\Actions\PurchaseOrder;

use App\Dtos\PurchaseOrder\FilterPurchaseOrdersDto;
use App\Models\PurchaseOrder\PurchaseOrder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FilterPurchaseOrdersAction
{
    public function execute(FilterPurchaseOrdersDto $dto): LengthAwarePaginator|Collection
    {
        $builder = PurchaseOrder::query()
            ->with('items.product')
            ->orderByDesc('created_at');

        return $dto->isToPaginate
            ? $builder->paginate(perPage: $dto->perPage, page: $dto->page)
            : $builder->get();
    }
}
