<?php

namespace App\Actions\Sale;

use App\Dtos\Sale\FilterSalesDto;
use App\Models\Sale\Sale;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FilterSalesAction
{
    public function execute(FilterSalesDto $dto): LengthAwarePaginator|Collection
    {
        $builder = Sale::query()
            ->with('items.product')
            ->orderByDesc('created_at');

        return $dto->isToPaginate
            ? $builder->paginate(perPage: $dto->perPage, page: $dto->page)
            : $builder->get();
    }
}
