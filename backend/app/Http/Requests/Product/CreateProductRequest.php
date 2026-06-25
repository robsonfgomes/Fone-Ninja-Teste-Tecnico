<?php

namespace App\Http\Requests\Product;

use App\Dtos\Product\CreateProductDto;
use App\Http\Requests\Abstract\AbstractRequest;

class CreateProductRequest extends AbstractRequest
{
    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'min:3'],
            'selling_price' => ['required', 'numeric', 'gt:0'],
            'initial_stock' => ['sometimes', 'integer', 'min:0'],
        ];
    }

    public function toDto(): CreateProductDto
    {
        return new CreateProductDto(
            name: $this->validated('name'),
            sellingPrice: $this->validated('selling_price'),
            initialStock: $this->validated('initial_stock', 0),
        );
    }
}
