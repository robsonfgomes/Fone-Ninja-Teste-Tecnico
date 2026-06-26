<?php

namespace App\Http\Requests\PurchaseOrder;

use App\Dtos\PurchaseOrder\FilterPurchaseOrdersDto;
use App\Http\Requests\Abstract\AbstractFilterRequest;

class FilterPurchaseOrdersRequest extends AbstractFilterRequest
{
    public function toDto(): FilterPurchaseOrdersDto
    {
        return new FilterPurchaseOrdersDto(
            page: $this->page(),
            perPage: $this->perPage(),
            isToPaginate: $this->isToPaginate(),
        );
    }
}
