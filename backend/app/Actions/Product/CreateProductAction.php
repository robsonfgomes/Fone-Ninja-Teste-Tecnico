<?php

namespace App\Actions\Product;

use App\Dtos\Product\CreateProductDto;
use App\Models\Product\Product;

class CreateProductAction
{
    public function execute(CreateProductDto $dto): Product
    {
        return Product::create([
            'name'          => $dto->name,
            'selling_price' => $dto->sellingPrice,
            'current_stock' => $dto->initialStock,
        ]);
    }
}
