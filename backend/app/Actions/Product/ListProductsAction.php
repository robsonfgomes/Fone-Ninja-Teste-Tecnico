<?php

namespace App\Actions\Product;

use App\Dtos\Product\ListProductsDto;
use App\Models\Product\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ListProductsAction
{
    public function execute(ListProductsDto $dto): LengthAwarePaginator
    {
        return Product::query()->orderBy('name')->paginate(
            perPage: $dto->perPage,
            page: $dto->page,
        );
    }
}
