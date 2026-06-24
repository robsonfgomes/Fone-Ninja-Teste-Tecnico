<?php

namespace Tests\Unit\Actions\Product;

use App\Actions\Product\UpdateProductStockAction;
use App\Models\Product\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateProductStockActionTest extends TestCase
{
    use RefreshDatabase;

    private UpdateProductStockAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new UpdateProductStockAction();
    }

    public function test_increments_stock(): void
    {
        $product = Product::create([
            'name'          => 'Fone X',
            'selling_price' => '200.00',
            'current_stock' => 5,
        ]);

        $this->action->execute($product, 10);

        $this->assertEquals(15, $product->fresh()->current_stock);
    }

    public function test_decrements_stock_with_negative_quantity(): void
    {
        $product = Product::create([
            'name'          => 'Fone X',
            'selling_price' => '200.00',
            'current_stock' => 10,
        ]);

        $this->action->execute($product, -3);

        $this->assertEquals(7, $product->fresh()->current_stock);
    }
}
