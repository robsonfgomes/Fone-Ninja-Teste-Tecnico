<?php

namespace App\Actions\Sale;

use App\Dtos\Sale\FilterSalesDto;
use App\Models\Sale\Sale;
use Illuminate\Pagination\LengthAwarePaginator;

class FilterSalesAction
{
    public function execute(FilterSalesDto $dto): LengthAwarePaginator
    {
        return Sale::query()
            ->with('items.product')
            ->orderByDesc('created_at')
            ->paginate(
                perPage: $dto->perPage,
                page: $dto->page
            );
    }
}
