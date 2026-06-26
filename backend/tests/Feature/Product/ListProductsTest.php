<?php

namespace Tests\Feature\Product;

use App\Models\Product\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListProductsTest extends TestCase
{
    use RefreshDatabase;

    private function createProduct(
        string $name = 'Fone Teste',
        float $sellingPrice = 200.00,
        int $stock = 10,
        ?float $averageCost = null,
    ): Product {
        return Product::create([
            'name'          => $name,
            'selling_price' => (string) $sellingPrice,
            'current_stock' => $stock,
            'average_cost'  => $averageCost !== null ? (string) $averageCost : null,
        ]);
    }

    public function test_returns_200_with_paginated_structure(): void
    {
        $this->createProduct();

        $response = $this->getJson('/api/produtos');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'sellingPrice', 'currentStock', 'averageCost', 'createdAt', 'updatedAt'],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_returns_empty_list_when_no_products(): void
    {
        $response = $this->getJson('/api/produtos');

        $response->assertStatus(200)
            ->assertJsonPath('data', [])
            ->assertJsonPath('meta.total', 0);
    }

    public function test_returns_correct_product_data(): void
    {
        $product = $this->createProduct('Fone X', 199.99, 5, 80.50);

        $response = $this->getJson('/api/produtos');

        $response->assertJsonPath('data.0.id', $product->id)
            ->assertJsonPath('data.0.name', 'Fone X')
            ->assertJsonPath('data.0.sellingPrice', 199.99)
            ->assertJsonPath('data.0.currentStock', 5)
            ->assertJsonPath('data.0.averageCost', 80.5);
    }

    public function test_returns_null_for_average_cost_when_not_set(): void
    {
        $this->createProduct('Fone X', 199.99, 5, null);

        $response = $this->getJson('/api/produtos');

        $response->assertJsonPath('data.0.averageCost', null);
    }

    public function test_products_ordered_by_created_at_desc(): void
    {
        $this->travel(-5)->minutes();
        $this->createProduct('Fone Antigo');
        $this->travelBack();
        $this->createProduct('Fone Novo');

        $response = $this->getJson('/api/produtos');

        $response->assertJsonPath('data.0.name', 'Fone Novo')
            ->assertJsonPath('data.1.name', 'Fone Antigo');
    }

    public function test_respects_per_page_parameter(): void
    {
        $this->createProduct('Fone A');
        $this->createProduct('Fone B');
        $this->createProduct('Fone C');

        $response = $this->getJson('/api/produtos?perPage=2');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.per_page', 2)
            ->assertJsonPath('meta.total', 3);
    }

    public function test_respects_page_parameter(): void
    {
        $this->createProduct('Fone A');
        $this->createProduct('Fone B');

        $response = $this->getJson('/api/produtos?perPage=1&page=2');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('meta.current_page', 2);
    }

    public function test_returns_422_for_invalid_page(): void
    {
        $response = $this->getJson('/api/produtos?page=0');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['page']);
    }

    public function test_returns_422_for_invalid_per_page(): void
    {
        $response = $this->getJson('/api/produtos?perPage=101');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['perPage']);
    }
}
