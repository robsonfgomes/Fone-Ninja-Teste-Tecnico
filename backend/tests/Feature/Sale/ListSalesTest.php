<?php

namespace Tests\Feature\Sale;

use App\Models\Product\Product;
use App\Models\Sale\Sale;
use App\Models\Sale\SaleItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListSalesTest extends TestCase
{
    use RefreshDatabase;

    private function createSaleWithItem(
        string $customerName,
        float $unitPrice,
        int $quantity,
        float $averageCost,
        string $status = 'Active',
    ): Sale {
        $product = Product::create([
            'name'          => 'Produto Teste',
            'selling_price' => '200.00',
            'current_stock' => 100,
            'average_cost'  => (string) $averageCost,
        ]);

        $sale = Sale::create([
            'customer_name' => $customerName,
            'status'        => $status,
        ]);

        SaleItem::create([
            'sale_id'    => $sale->id,
            'product_id' => $product->id,
            'quantity'   => $quantity,
            'unit_price' => (string) $unitPrice,
        ]);

        return $sale;
    }

    public function test_returns_200_with_paginated_structure(): void
    {
        $this->createSaleWithItem('Cliente A', 100.00, 2, 50.00);

        $response = $this->getJson('/api/vendas');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'customerName', 'status', 'totalAmount', 'profit', 'createdAt', 'updatedAt'],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_returns_empty_list_when_no_sales(): void
    {
        $response = $this->getJson('/api/vendas');

        $response->assertStatus(200)
            ->assertJsonPath('data', [])
            ->assertJsonPath('meta.total', 0);
    }

    public function test_calculates_total_correctly(): void
    {
        // total = 80 * 3 = 240
        $this->createSaleWithItem('Cliente A', 80.00, 3, 30.00);

        $response = $this->getJson('/api/vendas');

        $response->assertJsonPath('data.0.totalAmount', 240);
    }

    public function test_calculates_profit_correctly(): void
    {
        // profit = (80 - 30) * 3 = 150
        $this->createSaleWithItem('Cliente A', 80.00, 3, 30.00);

        $response = $this->getJson('/api/vendas');

        $response->assertJsonPath('data.0.profit', 150);
    }

    public function test_returns_customer_name_and_status(): void
    {
        $this->createSaleWithItem('João Silva', 50.00, 1, 20.00, 'Active');

        $response = $this->getJson('/api/vendas');

        $response->assertJsonPath('data.0.customerName', 'João Silva')
            ->assertJsonPath('data.0.status', 'Active');
    }

    public function test_respects_per_page_parameter(): void
    {
        $this->createSaleWithItem('Cliente A', 50.00, 1, 10.00);
        $this->createSaleWithItem('Cliente B', 50.00, 1, 10.00);
        $this->createSaleWithItem('Cliente C', 50.00, 1, 10.00);

        $response = $this->getJson('/api/vendas?perPage=2');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.per_page', 2)
            ->assertJsonPath('meta.total', 3);
    }

    public function test_respects_page_parameter(): void
    {
        $this->createSaleWithItem('Cliente A', 50.00, 1, 10.00);
        $this->createSaleWithItem('Cliente B', 50.00, 1, 10.00);

        $response = $this->getJson('/api/vendas?perPage=1&page=2');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('meta.current_page', 2);
    }

    public function test_returns_422_for_invalid_page(): void
    {
        $response = $this->getJson('/api/vendas?page=0');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['page']);
    }

    public function test_returns_422_for_invalid_per_page(): void
    {
        $response = $this->getJson('/api/vendas?perPage=101');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['perPage']);
    }

    public function test_profit_is_zero_when_average_cost_is_null(): void
    {
        $product = Product::create([
            'name'          => 'Produto Sem Custo',
            'selling_price' => '100.00',
            'current_stock' => 10,
        ]);

        $sale = Sale::create(['customer_name' => 'Cliente', 'status' => 'Active']);

        SaleItem::create([
            'sale_id'    => $sale->id,
            'product_id' => $product->id,
            'quantity'   => 2,
            'unit_price' => '60.00',
        ]);

        $response = $this->getJson('/api/vendas');

        // profit = (60 - 0) * 2 = 120
        $response->assertJsonPath('data.0.profit', 120);
    }
}
