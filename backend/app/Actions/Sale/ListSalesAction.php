<?php

namespace App\Actions\Sale;

use App\Dtos\Sale\ListSalesDto;
use App\Dtos\Sale\SaleListItemDto;
use App\Models\Sale\Sale;
use Illuminate\Pagination\LengthAwarePaginator;

class ListSalesAction
{
    public function execute(ListSalesDto $dto): LengthAwarePaginator
    {
        return Sale::query()
            ->with('items.product')
            ->orderByDesc('created_at')
            ->paginate(perPage: $dto->perPage, page: $dto->page)
            ->through(fn(Sale $sale) => new SaleListItemDto(
                id: $sale->id,
                customerName: $sale->customer_name,
                status: $sale->status->value,
                totalAmount: round(
                    $sale->items->sum(fn($item) => (float) $item->unit_price * $item->quantity),
                    2
                ),
                profit: round(
                    $sale->items->sum(fn($item) => ((float) $item->unit_price - (float) ($item->product?->average_cost ?? 0)) * $item->quantity),
                    2
                ),
                createdAt: $sale->getCreatedAt(),
                updatedAt: $sale->getUpdatedAt(),
            ));
    }
}
