<?php

namespace Tests\Unit\Models\Product;

use App\Models\Product\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductAverageCostTest extends TestCase
{
    use RefreshDatabase;

    public function test_sets_average_cost_when_no_previous_cost(): void
    {
        $product = Product::create([
            'name'          => 'Fone X',
            'selling_price' => '200.00',
            'current_stock' => 0,
            'average_cost'  => null,
        ]);

        // new_avg = (0 * 0 + 10 * 50) / (0 + 10) = 50.00
        $product->updateAverageCost(10, '50.00');

        $this->assertEquals('50.00', $product->fresh()->average_cost);
    }

    public function test_calculates_weighted_average_with_existing_stock(): void
    {
        $product = Product::create([
            'name'          => 'Fone X',
            'selling_price' => '200.00',
            'current_stock' => 10,
            'average_cost'  => '50.00',
        ]);

        // new_avg = (10 * 50 + 20 * 30) / (10 + 20) = 1100 / 30 = 36.67
        $product->updateAverageCost(20, '30.00');

        $this->assertEquals('36.67', $product->fresh()->average_cost);
    }
}
