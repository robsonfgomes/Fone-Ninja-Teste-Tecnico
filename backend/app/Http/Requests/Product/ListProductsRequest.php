<?php

namespace App\Http\Requests\Product;

use App\Dtos\Product\ListProductsDto;
use App\Http\Requests\AbstractRequest;

class ListProductsRequest extends AbstractRequest
{
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
            page: $this->validated('page', 1),
            perPage: $this->validated('per_page', 15),
        );
    }
}
