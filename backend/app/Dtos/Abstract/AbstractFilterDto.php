<?php

namespace App\Dtos\Abstract;

use App\Interfaces\DtoInterface;

abstract class AbstractFilterDto implements DtoInterface
{
    public function __construct(
        public readonly int $page,
        public readonly int $perPage,
    ) {}

}
