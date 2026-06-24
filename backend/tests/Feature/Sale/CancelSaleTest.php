<?php

namespace Tests\Feature\Sale;

use App\Enums\SaleStatusEnum;
use App\Models\Product\Product;
use App\Models\Sale\Sale;
use App\Models\Sale\SaleItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CancelSaleTest extends TestCase
{
    use RefreshDatabase;

    private function createActiveSaleWithProduct(int $stock = 10, int $quantity = 3): array
    {
        $product = Product::create([
            'name'          => 'Fone X',
            'selling_price' => '200.00',
            'current_stock' => $stock,
            'average_cost'  => '50.00',
        ]);

        $sale = Sale::create(['customer_name' => 'Fulano', 'status' => SaleStatusEnum::Active]);
        SaleItem::create([
            'sale_id'    => $sale->id,
            'product_id' => $product->id,
            'quantity'   => $quantity,
            'unit_price' => '80.00',
        ]);

        return [$sale, $product];
    }

    public function test_cancels_sale_and_returns_200(): void
    {
        [$sale] = $this->createActiveSaleWithProduct();

        $response = $this->patchJson("/api/vendas/{$sale->id}", [
            'status' => 'Cancelled',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['saleId', 'status', 'createdAt', 'updatedAt'],
            ])
            ->assertJsonPath('data.status', 'Cancelled');
    }

    public function test_restores_stock_on_cancellation(): void
    {
        [$sale, $product] = $this->createActiveSaleWithProduct(stock: 7, quantity: 3);

        $this->patchJson("/api/vendas/{$sale->id}", ['status' => 'Cancelled']);

        $this->assertEquals(10, $product->fresh()->current_stock);
    }

    public function test_returns_422_when_sale_already_cancelled(): void
    {
        $sale = Sale::create(['customer_name' => 'Fulano', 'status' => SaleStatusEnum::Cancelled]);

        $response = $this->patchJson("/api/vendas/{$sale->id}", ['status' => 'Cancelled']);

        $response->assertStatus(422)
            ->assertJsonPath('message', 'Esta venda já foi cancelada.');
    }

    public function test_returns_404_when_sale_not_found(): void
    {
        $response = $this->patchJson('/api/vendas/00000000-0000-0000-0000-000000000000', [
            'status' => 'Cancelled',
        ]);

        $response->assertStatus(404);
    }

    public function test_returns_422_when_status_is_missing(): void
    {
        [$sale] = $this->createActiveSaleWithProduct();

        $response = $this->patchJson("/api/vendas/{$sale->id}", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }

    public function test_returns_422_when_status_is_invalid(): void
    {
        [$sale] = $this->createActiveSaleWithProduct();

        $response = $this->patchJson("/api/vendas/{$sale->id}", ['status' => 'invalid_status']);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }
}
