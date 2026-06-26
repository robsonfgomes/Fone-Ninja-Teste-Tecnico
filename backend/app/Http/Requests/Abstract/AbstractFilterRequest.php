<?php

namespace App\Http\Requests\Abstract;

abstract class AbstractFilterRequest extends AbstractRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->has('isToPaginate')) {
            $this->merge(['isToPaginate' => filter_var($this->isToPaginate, FILTER_VALIDATE_BOOLEAN)]);
        }
    }

    public function rules(): array
    {
        return [
            'page'          => ['integer', 'min:1'],
            'perPage'       => ['integer', 'min:1', 'max:100'],
            'isToPaginate'  => ['boolean'],
        ];
    }

    protected function page(): int
    {
        return $this->validated('page', 1);
    }

    protected function perPage(): int
    {
        return $this->validated('perPage', 10);
    }


    protected function isToPaginate(): bool
    {
        return $this->validated('isToPaginate', true);
    }
}
