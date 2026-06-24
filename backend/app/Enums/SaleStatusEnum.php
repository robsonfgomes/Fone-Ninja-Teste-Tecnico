<?php

namespace App\Enums;

use App\Traits\EnumValues;

enum SaleStatusEnum: string
{
    use EnumValues;

    case Active    = 'Active';
    case Cancelled = 'Cancelled';
}
