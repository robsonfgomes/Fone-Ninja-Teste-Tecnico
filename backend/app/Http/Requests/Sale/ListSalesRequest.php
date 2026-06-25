<?php

namespace App\Http\Requests\Sale;

use App\Dtos\Sale\ListSalesDto;
use App\Http\Requests\AbstractRequest;

class ListSalesRequest extends AbstractRequest
{
    public function rules(): array
    {
        return [
            'page'     => ['integer', 'min:1'],
            'per_page' => ['integer', 'min:1', 'max:100'],
        ];
    }

    public function toDto(): ListSalesDto
    {
        return new ListSalesDto(
            page: $this->validated('page', 1),
            perPage: $this->validated('per_page', 10),
        );
    }
}
