<?php

namespace Tests\Unit\Actions\Product;

use App\Actions\Product\CreateProductAction;
use App\Dtos\Product\CreateProductDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateProductActionTest extends TestCase
{
    use RefreshDatabase;

    private CreateProductAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = app(CreateProductAction::class);
    }

    public function test_creates_product_in_database(): void
    {
        $dto = new CreateProductDto(
            name: 'Fone Bluetooth X',
            sellingPrice: '299.90',
            initialStock: 0,
        );

        $this->action->execute($dto);

        $this->assertDatabaseHas('products', [
            'name'          => 'Fone Bluetooth X',
            'selling_price' => 299.90,
        ]);
    }

    public function test_returns_created_product(): void
    {
        $dto = new CreateProductDto(
            name: 'Fone Bluetooth X',
            sellingPrice: '299.90',
            initialStock: 0,
        );

        $product = $this->action->execute($dto);

        $this->assertEquals('Fone Bluetooth X', $product->name);
        $this->assertEquals('299.90', $product->selling_price);
    }

    public function test_sets_provided_initial_stock(): void
    {
        $dto = new CreateProductDto(
            name: 'Fone Bluetooth X',
            sellingPrice: '299.90',
            initialStock: 10,
        );

        $product = $this->action->execute($dto);

        $this->assertEquals(10, $product->current_stock);
        $this->assertDatabaseHas('products', ['current_stock' => 10]);
    }

    public function test_sets_stock_to_zero_when_initial_stock_is_zero(): void
    {
        $dto = new CreateProductDto(
            name: 'Fone Bluetooth X',
            sellingPrice: '299.90',
            initialStock: 0,
        );

        $product = $this->action->execute($dto);

        $this->assertEquals(0, $product->current_stock);
        $this->assertDatabaseHas('products', ['current_stock' => 0]);
    }

    public function test_average_cost_is_null_on_creation(): void
    {
        $dto = new CreateProductDto(
            name: 'Fone Bluetooth X',
            sellingPrice: '299.90',
            initialStock: 0,
        );

        $product = $this->action->execute($dto);

        $this->assertNull($product->average_cost);
        $this->assertDatabaseHas('products', ['average_cost' => null]);
    }
}
