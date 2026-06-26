<?php

namespace Tests\Unit\Actions\Product;

use App\Actions\Product\FilterProductsAction;
use App\Dtos\Product\FilterProductsDto;
use App\Models\Product\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class FilterProductsActionTest extends TestCase
{
    use RefreshDatabase;

    private FilterProductsAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = app(FilterProductsAction::class);
    }

    private function createProduct(string $name = 'Fone Teste'): Product
    {
        return Product::create([
            'name'          => $name,
            'selling_price' => '200.00',
            'current_stock' => 10,
        ]);
    }

    public function test_returns_paginated_collection_when_is_to_paginate_is_true(): void
    {
        $this->createProduct();

        $dto = new FilterProductsDto(page: 1, perPage: 10, isToPaginate: true);

        $result = $this->action->execute($dto);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    public function test_returns_all_products_without_pagination(): void
    {
        $this->createProduct('Fone A');
        $this->createProduct('Fone B');
        $this->createProduct('Fone C');

        $dto = new FilterProductsDto(page: 1, perPage: 10, isToPaginate: false);

        $result = $this->action->execute($dto);

        $this->assertNotInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertCount(3, $result);
    }

    public function test_respects_per_page_in_paginated_result(): void
    {
        $this->createProduct('Fone A');
        $this->createProduct('Fone B');
        $this->createProduct('Fone C');

        $dto = new FilterProductsDto(page: 1, perPage: 2, isToPaginate: true);

        $result = $this->action->execute($dto);

        $this->assertCount(2, $result->items());
        $this->assertEquals(3, $result->total());
    }

    public function test_respects_page_in_paginated_result(): void
    {
        $this->createProduct('Fone A');
        $this->createProduct('Fone B');
        $this->createProduct('Fone C');

        $dto = new FilterProductsDto(page: 2, perPage: 2, isToPaginate: true);

        $result = $this->action->execute($dto);

        $this->assertCount(1, $result->items());
        $this->assertEquals(2, $result->currentPage());
    }

    public function test_returns_products_ordered_by_created_at_desc(): void
    {
        $this->travel(-5)->minutes();
        $this->createProduct('Fone Antigo');
        $this->travelBack();
        $this->createProduct('Fone Novo');

        $dto = new FilterProductsDto(page: 1, perPage: 10, isToPaginate: false);

        $result = $this->action->execute($dto);

        $this->assertEquals('Fone Novo', $result->first()->name);
        $this->assertEquals('Fone Antigo', $result->last()->name);
    }

    public function test_returns_empty_collection_when_no_products(): void
    {
        $dto = new FilterProductsDto(page: 1, perPage: 10, isToPaginate: false);

        $result = $this->action->execute($dto);

        $this->assertCount(0, $result);
    }
}
