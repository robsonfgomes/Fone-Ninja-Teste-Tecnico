<?php

namespace Tests\Unit\Models\Product;

use App\Models\Product\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductStockTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->product = Product::create([
            'name'          => 'Fone X',
            'selling_price' => '200.00',
            'current_stock' => 10,
        ]);
    }

    public function test_increments_stock(): void
    {
        $this->product->incrementStock(5);

        $this->assertEquals(15, $this->product->fresh()->current_stock);
    }

    public function test_decrements_stock(): void
    {
        $this->product->decrementStock(3);

        $this->assertEquals(7, $this->product->fresh()->current_stock);
    }
}
