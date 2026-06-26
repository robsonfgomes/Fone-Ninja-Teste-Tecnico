<?php

namespace App\Actions\Product;

use App\Dtos\Product\FilterProductsDto;
use App\Models\Product\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FilterProductsAction
{
    public function execute(FilterProductsDto $dto): LengthAwarePaginator|Collection
    {
        $builder = Product::query()->orderByDesc('created_at');

        return $dto->isToPaginate
            ? $builder->paginate(perPage: $dto->perPage, page: $dto->page)
            : $builder->get();
    }
}
