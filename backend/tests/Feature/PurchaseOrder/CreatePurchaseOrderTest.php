<?php

namespace Tests\Feature\PurchaseOrder;

use App\Models\Product\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatePurchaseOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_registers_purchase_and_returns_201(): void
    {
        $product = Product::create([
            'name'          => 'Fone X',
            'selling_price' => '200.00',
            'current_stock' => 0,
        ]);

        $response = $this->postJson('/api/compras', [
            'supplier' => 'Fornecedor X',
            'products' => [
                ['id' => $product->id, 'quantity' => 50, 'unit_price' => 20.00],
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['purchaseOrderId', 'totalAmount', 'createdAt', 'updatedAt'],
            ])
            ->assertJsonPath('data.totalAmount', 1000);
    }

    public function test_updates_stock_after_purchase(): void
    {
        $product = Product::create([
            'name'          => 'Fone X',
            'selling_price' => '200.00',
            'current_stock' => 10,
        ]);

        $this->postJson('/api/compras', [
            'supplier' => 'Fornecedor X',
            'products' => [
                ['id' => $product->id, 'quantity' => 50, 'unit_price' => 20.00],
            ],
        ]);

        $this->assertEquals(60, $product->fresh()->current_stock);
    }

    public function test_updates_average_cost_after_purchase(): void
    {
        $product = Product::create([
            'name'          => 'Fone X',
            'selling_price' => '200.00',
            'current_stock' => 10,
            'average_cost'  => '50.00',
        ]);

        // new_avg = (10 * 50 + 20 * 30) / (10 + 20) = 1100 / 30 = 36.67
        $this->postJson('/api/compras', [
            'supplier' => 'Fornecedor X',
            'products' => [
                ['id' => $product->id, 'quantity' => 20, 'unit_price' => 30.00],
            ],
        ]);

        $this->assertEquals('36.67', $product->fresh()->average_cost);
    }

    public function test_calculates_total_amount_for_multiple_products(): void
    {
        $product1 = Product::create(['name' => 'Fone A', 'selling_price' => '200.00', 'current_stock' => 0]);
        $product2 = Product::create(['name' => 'Fone B', 'selling_price' => '150.00', 'current_stock' => 0]);

        $response = $this->postJson('/api/compras', [
            'supplier' => 'Fornecedor X',
            'products' => [
                ['id' => $product1->id, 'quantity' => 50, 'unit_price' => 20.00],
                ['id' => $product2->id, 'quantity' => 30, 'unit_price' => 10.00],
            ],
        ]);

        // 50*20 + 30*10 = 1000 + 300 = 1300
        $response->assertStatus(201)
            ->assertJsonPath('data.totalAmount', 1300);
    }

    public function test_returns_422_when_supplier_is_missing(): void
    {
        $product = Product::create([
            'name'          => 'Fone X',
            'selling_price' => '200.00',
            'current_stock' => 0,
        ]);

        $response = $this->postJson('/api/compras', [
            'products' => [
                ['id' => $product->id, 'quantity' => 10, 'unit_price' => 20.00],
            ],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['supplier']);
    }

    public function test_returns_422_when_product_does_not_exist(): void
    {
        $response = $this->postJson('/api/compras', [
            'supplier' => 'Fornecedor X',
            'products' => [
                ['id' => '00000000-0000-0000-0000-000000000000', 'quantity' => 10, 'unit_price' => 20.00],
            ],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['products.0.id']);
    }

    public function test_returns_422_when_products_array_is_empty(): void
    {
        $response = $this->postJson('/api/compras', [
            'supplier' => 'Fornecedor X',
            'products' => [],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['products']);
    }

    public function test_returns_422_when_duplicate_product_ids_in_same_order(): void
    {
        $product = Product::create([
            'name'          => 'Fone X',
            'selling_price' => '200.00',
            'current_stock' => 0,
        ]);

        $response = $this->postJson('/api/compras', [
            'supplier' => 'Fornecedor X',
            'products' => [
                ['id' => $product->id, 'quantity' => 10, 'unit_price' => 20.00],
                ['id' => $product->id, 'quantity' => 5,  'unit_price' => 25.00],
            ],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['products.0.id', 'products.1.id']);
    }
}
