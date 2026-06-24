<?php

namespace App\Exceptions;

use Exception;

class SaleAlreadyCancelledException extends Exception
{
    public function __construct()
    {
        parent::__construct('Esta venda já foi cancelada.');
    }
}
