<?php

namespace App\Http\Requests\Sale;

use App\Dtos\Sale\FilterSalesDto;
use App\Http\Requests\Abstract\AbstractFilterRequest;

class FilterSalesRequest extends AbstractFilterRequest
{
    public function toDto(): FilterSalesDto
    {
        return new FilterSalesDto(
            page: $this->validated('page', 1),
            perPage: $this->validated('per_page', 10),
        );
    }
}
