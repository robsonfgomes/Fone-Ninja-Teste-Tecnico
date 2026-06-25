<?php

namespace App\Http\Requests\PurchaseOrder;

use App\Dtos\PurchaseOrder\CreatePurchaseOrderDto;
use App\Dtos\PurchaseOrder\PurchaseOrderItemDto;
use App\Http\Requests\Abstract\AbstractRequest;

class CreatePurchaseOrderRequest extends AbstractRequest
{
    public function rules(): array
    {
        return [
            'supplier'              => ['required', 'string', 'min:3'],
            'products'              => ['required', 'array', 'min:1'],
            'products.*.id'         => ['required', 'uuid', 'exists:products,id', 'distinct'],
            'products.*.quantity'   => ['required', 'integer', 'min:1'],
            'products.*.unit_price' => ['required', 'numeric', 'gt:0'],
        ];
    }

    public function toDto(): CreatePurchaseOrderDto
    {
        $items = array_map(
            fn(array $item) => new PurchaseOrderItemDto(
                productId: $item['id'],
                quantity: $item['quantity'],
                unitPrice: (float) $item['unit_price'],
            ),
            $this->validated('products'),
        );

        return new CreatePurchaseOrderDto(
            supplier: $this->validated('supplier'),
            items: $items,
        );
    }
}
