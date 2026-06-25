<?php

namespace App\Http\Requests\Product;

use App\Dtos\Product\FilterProductsDto;
use App\Http\Requests\AbstractFilterRequest;

class FilterProductsRequest extends AbstractFilterRequest
{
    public function toDto(): FilterProductsDto
    {
        return new FilterProductsDto(
            page: $this->validated('page', 1),
            perPage: $this->validated('per_page', 10),
        );
    }
}
