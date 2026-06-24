<?php

namespace App\Http\Requests\Sale;

use App\Enums\SaleStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSaleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in([SaleStatusEnum::Cancelled->value])],
        ];
    }
}
