<?php

namespace App\Actions\Sale;

use App\Dtos\Sale\ListSalesDto;
use App\Models\Sale\Sale;
use Illuminate\Pagination\LengthAwarePaginator;

class ListSalesAction
{
    public function execute(ListSalesDto $dto): LengthAwarePaginator
    {
        return Sale::query()
            ->with('items.product')
            ->orderByDesc('created_at')
            ->paginate(
                perPage: $dto->perPage,
                page: $dto->page,
            );
    }
}
