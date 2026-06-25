<?php

namespace App\Dtos\Abstract;

use App\Interfaces\DtoInterface;

abstract class AbstractFilterDto implements DtoInterface
{
    public function __construct(
        public readonly int $page,
        public readonly int $perPage,
    ) {}

    public static function fromArray(array $data): static
    {
        return new static(
            page: $data['page'] ?? 1,
            perPage: $data['per_page'] ?? 10,
        );
    }
}
