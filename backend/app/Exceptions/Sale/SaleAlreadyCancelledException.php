<?php

namespace App\Exceptions\Sale;

use Exception;

class SaleAlreadyCancelledException extends Exception
{
    public function __construct()
    {
        parent::__construct('Esta venda já foi cancelada.');
    }
}
