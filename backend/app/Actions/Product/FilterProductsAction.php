<?php

namespace App\Actions\Product;

use App\Dtos\Product\FilterProductsDto;
use App\Models\Product\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class FilterProductsAction
{
    public function execute(FilterProductsDto $dto): LengthAwarePaginator
    {
        return Product::query()
            ->orderByDesc('created_at')
            ->paginate(
                perPage: $dto->perPage,
                page: $dto->page,
            );
    }
}
