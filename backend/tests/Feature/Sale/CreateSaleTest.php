<?php

namespace Tests\Feature\Sale;

use App\Models\Product\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateSaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_registers_sale_and_returns_201(): void
    {
        $product = Product::create([
            'name'          => 'Fone X',
            'selling_price' => '200.00',
            'current_stock' => 10,
            'average_cost'  => '50.00',
        ]);

        $response = $this->postJson('/api/vendas', [
            'customer' => 'Fulano da Silva',
            'products' => [
                ['id' => $product->id, 'quantity' => 2, 'unit_price' => 80.00],
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['saleId', 'totalAmount', 'profit', 'createdAt', 'updatedAt'],
            ]);
    }

    public function test_deducts_stock_after_sale(): void
    {
        $product = Product::create([
            'name'          => 'Fone X',
            'selling_price' => '200.00',
            'current_stock' => 10,
            'average_cost'  => '50.00',
        ]);

        $this->postJson('/api/vendas', [
            'customer' => 'Fulano da Silva',
            'products' => [
                ['id' => $product->id, 'quantity' => 3, 'unit_price' => 80.00],
            ],
        ]);

        $this->assertEquals(7, $product->fresh()->current_stock);
    }

    public function test_calculates_total_amount(): void
    {
        $product = Product::create([
            'name'          => 'Fone X',
            'selling_price' => '200.00',
            'current_stock' => 10,
            'average_cost'  => '50.00',
        ]);

        // 2 * 80 = 160
        $response = $this->postJson('/api/vendas', [
            'customer' => 'Fulano da Silva',
            'products' => [
                ['id' => $product->id, 'quantity' => 2, 'unit_price' => 80.00],
            ],
        ]);

        $response->assertJsonPath('data.totalAmount', 160);
    }

    public function test_calculates_profit(): void
    {
        $product = Product::create([
            'name'          => 'Fone X',
            'selling_price' => '200.00',
            'current_stock' => 10,
            'average_cost'  => '50.00',
        ]);

        // profit = (80 - 50) * 2 = 60
        $response = $this->postJson('/api/vendas', [
            'customer' => 'Fulano da Silva',
            'products' => [
                ['id' => $product->id, 'quantity' => 2, 'unit_price' => 80.00],
            ],
        ]);

        $response->assertJsonPath('data.profit', 60);
    }

    public function test_calculates_total_and_profit_for_multiple_products(): void
    {
        $product1 = Product::create([
            'name'          => 'Fone A',
            'selling_price' => '200.00',
            'current_stock' => 10,
            'average_cost'  => '30.00',
        ]);
        $product2 = Product::create([
            'name'          => 'Fone B',
            'selling_price' => '150.00',
            'current_stock' => 5,
            'average_cost'  => '20.00',
        ]);

        // total = 2*80 + 1*100 = 260
        // profit = (80-30)*2 + (100-20)*1 = 100 + 80 = 180
        $response = $this->postJson('/api/vendas', [
            'customer' => 'Fulano da Silva',
            'products' => [
                ['id' => $product1->id, 'quantity' => 2, 'unit_price' => 80.00],
                ['id' => $product2->id, 'quantity' => 1, 'unit_price' => 100.00],
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.totalAmount', 260)
            ->assertJsonPath('data.profit', 180);
    }

    public function test_profit_is_zero_when_average_cost_is_null(): void
    {
        $product = Product::create([
            'name'          => 'Fone X',
            'selling_price' => '200.00',
            'current_stock' => 10,
        ]);

        // average_cost is null → treat as 0 → profit = (50 - 0) * 2 = 100
        $response = $this->postJson('/api/vendas', [
            'customer' => 'Fulano da Silva',
            'products' => [
                ['id' => $product->id, 'quantity' => 2, 'unit_price' => 50.00],
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.profit', 100);
    }

    public function test_returns_422_when_cliente_is_missing(): void
    {
        $product = Product::create([
            'name'          => 'Fone X',
            'selling_price' => '200.00',
            'current_stock' => 10,
        ]);

        $response = $this->postJson('/api/vendas', [
            'products' => [
                ['id' => $product->id, 'quantity' => 1, 'unit_price' => 80.00],
            ],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['customer']);
    }

    public function test_returns_422_when_product_does_not_exist(): void
    {
        $response = $this->postJson('/api/vendas', [
            'customer' => 'Fulano da Silva',
            'products' => [
                ['id' => '00000000-0000-0000-0000-000000000000', 'quantity' => 1, 'unit_price' => 80.00],
            ],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['products.0.id']);
    }

    public function test_returns_422_when_insufficient_stock(): void
    {
        $product = Product::create([
            'name'          => 'Fone X',
            'selling_price' => '200.00',
            'current_stock' => 2,
        ]);

        $response = $this->postJson('/api/vendas', [
            'customer' => 'Fulano da Silva',
            'products' => [
                ['id' => $product->id, 'quantity' => 5, 'unit_price' => 80.00],
            ],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['products.0.quantity']);
    }

    public function test_returns_422_when_products_array_is_empty(): void
    {
        $response = $this->postJson('/api/vendas', [
            'customer' => 'Fulano da Silva',
            'products' => [],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['products']);
    }

    public function test_returns_422_when_duplicate_product_ids(): void
    {
        $product = Product::create([
            'name'          => 'Fone X',
            'selling_price' => '200.00',
            'current_stock' => 20,
        ]);

        $response = $this->postJson('/api/vendas', [
            'customer' => 'Fulano da Silva',
            'products' => [
                ['id' => $product->id, 'quantity' => 2, 'unit_price' => 80.00],
                ['id' => $product->id, 'quantity' => 1, 'unit_price' => 90.00],
            ],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['products.0.id', 'products.1.id']);
    }
}
