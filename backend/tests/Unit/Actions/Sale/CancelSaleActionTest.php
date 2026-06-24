<?php

namespace Tests\Unit\Actions\Sale;

use App\Actions\Sale\CancelSaleAction;
use App\Enums\SaleStatusEnum;
use App\Exceptions\SaleAlreadyCancelledException;
use App\Models\Product\Product;
use App\Models\Sale\Sale;
use App\Models\Sale\SaleItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CancelSaleActionTest extends TestCase
{
    use RefreshDatabase;

    private CancelSaleAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = app(CancelSaleAction::class);
    }

    public function test_cancels_sale_and_updates_status(): void
    {
        $product = Product::create([
            'name'          => 'Fone X',
            'selling_price' => '200.00',
            'current_stock' => 5,
            'average_cost'  => '50.00',
        ]);

        $sale = Sale::create(['customer_name' => 'Fulano', 'status' => SaleStatusEnum::Active]);
        SaleItem::create([
            'sale_id'    => $sale->id,
            'product_id' => $product->id,
            'quantity'   => 3,
            'unit_price' => '80.00',
        ]);

        $result = $this->action->execute($sale);

        $this->assertEquals(SaleStatusEnum::Cancelled, $result->status);
        $this->assertDatabaseHas('sales', ['id' => $sale->id, 'status' => 'Cancelled']);
    }

    public function test_restores_product_stock_on_cancellation(): void
    {
        $product = Product::create([
            'name'          => 'Fone X',
            'selling_price' => '200.00',
            'current_stock' => 5,
            'average_cost'  => '50.00',
        ]);

        $sale = Sale::create(['customer_name' => 'Fulano', 'status' => SaleStatusEnum::Active]);
        SaleItem::create([
            'sale_id'    => $sale->id,
            'product_id' => $product->id,
            'quantity'   => 3,
            'unit_price' => '80.00',
        ]);

        $this->action->execute($sale);

        $this->assertEquals(8, $product->fresh()->current_stock);
    }

    public function test_restores_stock_for_multiple_items(): void
    {
        $product1 = Product::create([
            'name'          => 'Fone A',
            'selling_price' => '200.00',
            'current_stock' => 5,
            'average_cost'  => '50.00',
        ]);
        $product2 = Product::create([
            'name'          => 'Fone B',
            'selling_price' => '150.00',
            'current_stock' => 10,
            'average_cost'  => '30.00',
        ]);

        $sale = Sale::create(['customer_name' => 'Fulano', 'status' => SaleStatusEnum::Active]);
        SaleItem::create(['sale_id' => $sale->id, 'product_id' => $product1->id, 'quantity' => 2, 'unit_price' => '80.00']);
        SaleItem::create(['sale_id' => $sale->id, 'product_id' => $product2->id, 'quantity' => 4, 'unit_price' => '60.00']);

        $this->action->execute($sale);

        $this->assertEquals(7, $product1->fresh()->current_stock);
        $this->assertEquals(14, $product2->fresh()->current_stock);
    }

    public function test_throws_exception_when_sale_already_cancelled(): void
    {
        $sale = Sale::create(['customer_name' => 'Fulano', 'status' => SaleStatusEnum::Cancelled]);

        $this->expectException(SaleAlreadyCancelledException::class);

        $this->action->execute($sale);
    }

    public function test_does_not_modify_stock_when_sale_already_cancelled(): void
    {
        $product = Product::create([
            'name'          => 'Fone X',
            'selling_price' => '200.00',
            'current_stock' => 5,
            'average_cost'  => '50.00',
        ]);

        $sale = Sale::create(['customer_name' => 'Fulano', 'status' => SaleStatusEnum::Cancelled]);
        SaleItem::create([
            'sale_id'    => $sale->id,
            'product_id' => $product->id,
            'quantity'   => 3,
            'unit_price' => '80.00',
        ]);

        try {
            $this->action->execute($sale);
        } catch (SaleAlreadyCancelledException) {
            // esperado
        }

        $this->assertEquals(5, $product->fresh()->current_stock);
    }
}
