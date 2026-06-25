<?php

namespace App\Http\Requests\Abstract;

abstract class AbstractFilterRequest extends AbstractRequest
{
    public function rules(): array
    {
        return [
            'page'     => ['integer', 'min:1'],
            'per_page' => ['integer', 'min:1', 'max:100'],
        ];
    }

    protected function page(): int
    {
        return $this->validated('page', 1);
    }

    protected function perPage(): int
    {
        return $this->validated('per_page', 10);
    }
}
