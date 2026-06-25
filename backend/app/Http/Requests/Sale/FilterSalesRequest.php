<?php

namespace App\Http\Requests\Sale;

use App\Dtos\Sale\FilterSalesDto;
use App\Http\Requests\Abstract\AbstractFilterRequest;

class FilterSalesRequest extends AbstractFilterRequest
{
    public function toDto(): FilterSalesDto
    {
        return FilterSalesDto::fromArray($this->validated());
    }
}
