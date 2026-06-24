<?php

namespace App\Models\Abstract;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractModel extends Model
{
    use HasUuids;

    public function getUpdatedAt(): string
    {
        return $this->updated_at->format('d/m/Y H:i:s');
    }

    public function getCreatedAt(): string
    {
        return $this->created_at->format('d/m/Y H:i:s');
    }
}
