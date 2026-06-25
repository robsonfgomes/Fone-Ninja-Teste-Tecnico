<?php

namespace App\Http\Requests\Product;

use App\Dtos\Product\FilterProductsDto;
use App\Http\Requests\Abstract\AbstractFilterRequest;

class FilterProductsRequest extends AbstractFilterRequest
{
    public function toDto(): FilterProductsDto
    {
        return FilterProductsDto::fromArray($this->validated());
    }
}
