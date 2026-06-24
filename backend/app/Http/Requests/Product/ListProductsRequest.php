<?php

namespace App\Http\Requests\Product;

use App\Dtos\Product\ListProductsDto;
use App\Http\Requests\AbstractRequest;

class ListProductsRequest extends AbstractRequest
{
    protected function prepareForValidation(): void
    {
        $this->mergeIfMissing([
            'page'     => 1,
            'per_page' => 15,
        ]);
    }

    public function rules(): array
    {
        return [
            'page'     => ['integer', 'min:1'],
            'per_page' => ['integer', 'min:1', 'max:100'],
        ];
    }

    public function toDto(): ListProductsDto
    {
        return new ListProductsDto(
            page: $this->validated('page'),
            perPage: $this->validated('per_page'),
        );
    }
}
