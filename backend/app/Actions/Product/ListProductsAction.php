<?php

namespace App\Actions\Product;

use App\Models\Product\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ListProductsAction
{
    public function execute(): LengthAwarePaginator
    {
        return Product::query()->orderBy('name')->paginate();
    }
}
