<?php

namespace App\Http\Requests;

use App\Interfaces\DtoInterface;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractRequest extends FormRequest
{
    abstract public function toDto(): DtoInterface;
}
