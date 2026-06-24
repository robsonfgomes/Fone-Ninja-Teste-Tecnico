<?php

namespace App\Enums\Sale;

enum SaleStatus: string
{
    case Active    = 'active';
    case Cancelled = 'cancelled';
}
