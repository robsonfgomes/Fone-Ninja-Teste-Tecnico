<?php

namespace Tests\Feature\PurchaseOrder;

use App\Models\Product\Product;
use App\Models\PurchaseOrder\PurchaseOrder;
use App\Models\PurchaseOrder\PurchaseOrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListPurchaseOrdersTest extends TestCase
{
    use RefreshDatabase;

    private function createOrderWithItem(
        string $supplierName,
        float $unitPrice,
        int $quantity,
    ): PurchaseOrder {
        $product = Product::create([
            'name'          => 'Produto Teste',
            'selling_price' => '200.00',
            'current_stock' => 100,
        ]);

        $order = PurchaseOrder::create([
            'supplier_name' => $supplierName,
        ]);

        PurchaseOrderItem::create([
            'purchase_order_id' => $order->id,
            'product_id'        => $product->id,
            'quantity'          => $quantity,
            'unit_price'        => (string) $unitPrice,
        ]);

        return $order;
    }

    public function test_returns_200_with_paginated_structure(): void
    {
        $this->createOrderWithItem('Fornecedor A', 100.00, 2);

        $response = $this->getJson('/api/compras');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'supplierName', 'totalAmount', 'createdAt', 'updatedAt'],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_returns_empty_list_when_no_orders(): void
    {
        $response = $this->getJson('/api/compras');

        $response->assertStatus(200)
            ->assertJsonPath('data', [])
            ->assertJsonPath('meta.total', 0);
    }

    public function test_calculates_total_amount_correctly(): void
    {
        // totalAmount = 80 * 3 = 240
        $this->createOrderWithItem('Fornecedor A', 80.00, 3);

        $response = $this->getJson('/api/compras');

        $response->assertJsonPath('data.0.totalAmount', 240);
    }

    public function test_returns_supplier_name(): void
    {
        $this->createOrderWithItem('Fornecedor XYZ', 50.00, 1);

        $response = $this->getJson('/api/compras');

        $response->assertJsonPath('data.0.supplierName', 'Fornecedor XYZ');
    }

    public function test_respects_per_page_parameter(): void
    {
        $this->createOrderWithItem('Fornecedor A', 50.00, 1);
        $this->createOrderWithItem('Fornecedor B', 50.00, 1);
        $this->createOrderWithItem('Fornecedor C', 50.00, 1);

        $response = $this->getJson('/api/compras?perPage=2');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.per_page', 2)
            ->assertJsonPath('meta.total', 3);
    }

    public function test_respects_page_parameter(): void
    {
        $this->createOrderWithItem('Fornecedor A', 50.00, 1);
        $this->createOrderWithItem('Fornecedor B', 50.00, 1);

        $response = $this->getJson('/api/compras?perPage=1&page=2');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('meta.current_page', 2);
    }

    public function test_returns_422_for_invalid_page(): void
    {
        $response = $this->getJson('/api/compras?page=0');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['page']);
    }

    public function test_returns_422_for_invalid_per_page(): void
    {
        $response = $this->getJson('/api/compras?perPage=101');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['perPage']);
    }
}
