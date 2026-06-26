<?php

namespace App\Http\Requests\Sale;

use App\Dtos\Sale\CreateSaleDto;
use App\Dtos\Sale\SaleItemDto;
use App\Http\Requests\Abstract\AbstractRequest;
use App\Models\Product\Product;

class CreateSaleRequest extends AbstractRequest
{
    public function rules(): array
    {
        return [
            'customer'                  => ['required', 'string', 'min:3', 'max:255'],
            'products'                  => ['required', 'array', 'min:1'],
            'products.*.id'             => ['required', 'uuid', 'exists:products,id', 'distinct'],
            'products.*.quantity'       => ['required', 'integer', 'min:1'],
            'products.*.unitPrice'      => ['required', 'numeric', 'gt:0'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            foreach ($this->validated('products') as $index => $item) {
                $product = Product::find($item['id']);

                if ($product && $product->current_stock < $item['quantity']) {
                    $validator->errors()->add(
                        "products.{$index}.quantity",
                        "\"{$product->name}\": apenas {$product->current_stock} em estoque.",
                    );
                }
            }
        });
    }

    public function toDto(): CreateSaleDto
    {
        $items = array_map(
            fn(array $item) => new SaleItemDto(
                productId: $item['id'],
                quantity: $item['quantity'],
                unitPrice: (float) $item['unitPrice'],
            ),
            $this->validated('products'),
        );

        return new CreateSaleDto(
            customer: $this->validated('customer'),
            items: $items,
        );
    }
}
