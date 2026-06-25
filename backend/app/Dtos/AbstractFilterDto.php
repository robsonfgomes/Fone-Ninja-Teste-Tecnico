<?php

namespace App\Dtos;

use App\Interfaces\DtoInterface;

abstract class AbstractFilterDto implements DtoInterface
{
    public function __construct(
        public readonly int $page,
        public readonly int $perPage,
    ) {}
}
