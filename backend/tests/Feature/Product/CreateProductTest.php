<?php

namespace Tests\Feature\Product;

use App\Models\Product\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_product_and_returns_201(): void
    {
        $response = $this->postJson('/api/produtos', [
            'name'          => 'Fone Bluetooth X',
            'selling_price' => 299.90,
        ]);

        $response->assertStatus(201);
    }

    public function test_response_has_correct_structure(): void
    {
        $response = $this->postJson('/api/produtos', [
            'name'          => 'Fone Bluetooth X',
            'selling_price' => 299.90,
        ]);

        $response->assertJsonStructure([
            'data' => ['id', 'name', 'sellingPrice', 'currentStock', 'averageCost', 'createdAt', 'updatedAt'],
        ]);
    }

    public function test_returns_correct_data_in_response(): void
    {
        $response = $this->postJson('/api/produtos', [
            'name'          => 'Fone Bluetooth X',
            'selling_price' => 299.90,
            'initial_stock' => 5,
        ]);

        $response->assertJsonPath('data.name', 'Fone Bluetooth X')
            ->assertJsonPath('data.sellingPrice', 299.90)
            ->assertJsonPath('data.currentStock', 5);
    }

    public function test_default_stock_is_zero_when_not_provided(): void
    {
        $response = $this->postJson('/api/produtos', [
            'name'          => 'Fone Bluetooth X',
            'selling_price' => 299.90,
        ]);

        $response->assertJsonPath('data.currentStock', 0);
        $this->assertDatabaseHas('products', ['current_stock' => 0]);
    }

    public function test_creates_with_provided_initial_stock(): void
    {
        $this->postJson('/api/produtos', [
            'name'          => 'Fone Bluetooth X',
            'selling_price' => 299.90,
            'initial_stock' => 15,
        ]);

        $this->assertDatabaseHas('products', ['current_stock' => 15]);
    }

    public function test_average_cost_is_null_on_creation(): void
    {
        $response = $this->postJson('/api/produtos', [
            'name'          => 'Fone Bluetooth X',
            'selling_price' => 299.90,
        ]);

        $response->assertJsonPath('data.averageCost', null);
        $this->assertDatabaseHas('products', ['average_cost' => null]);
    }

    public function test_persists_product_in_database(): void
    {
        $this->postJson('/api/produtos', [
            'name'          => 'Fone Bluetooth X',
            'selling_price' => 299.90,
        ]);

        $this->assertDatabaseHas('products', [
            'name'          => 'Fone Bluetooth X',
            'selling_price' => 299.90,
        ]);
    }

    public function test_returns_422_when_name_is_missing(): void
    {
        $response = $this->postJson('/api/produtos', [
            'selling_price' => 299.90,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_returns_422_when_name_is_too_short(): void
    {
        $response = $this->postJson('/api/produtos', [
            'name'          => 'AB',
            'selling_price' => 299.90,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_returns_422_when_name_exceeds_max_length(): void
    {
        $response = $this->postJson('/api/produtos', [
            'name'          => str_repeat('A', 256),
            'selling_price' => 299.90,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_returns_422_when_selling_price_is_missing(): void
    {
        $response = $this->postJson('/api/produtos', [
            'name' => 'Fone Bluetooth X',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['selling_price']);
    }

    public function test_returns_422_when_selling_price_is_not_numeric(): void
    {
        $response = $this->postJson('/api/produtos', [
            'name'          => 'Fone Bluetooth X',
            'selling_price' => 'caro',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['selling_price']);
    }

    public function test_returns_422_when_selling_price_is_zero(): void
    {
        $response = $this->postJson('/api/produtos', [
            'name'          => 'Fone Bluetooth X',
            'selling_price' => 0,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['selling_price']);
    }

    public function test_returns_422_when_selling_price_is_negative(): void
    {
        $response = $this->postJson('/api/produtos', [
            'name'          => 'Fone Bluetooth X',
            'selling_price' => -10,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['selling_price']);
    }

    public function test_returns_422_when_initial_stock_is_negative(): void
    {
        $response = $this->postJson('/api/produtos', [
            'name'          => 'Fone Bluetooth X',
            'selling_price' => 299.90,
            'initial_stock' => -1,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['initial_stock']);
    }

    public function test_returns_422_when_initial_stock_is_not_integer(): void
    {
        $response = $this->postJson('/api/produtos', [
            'name'          => 'Fone Bluetooth X',
            'selling_price' => 299.90,
            'initial_stock' => 3.5,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['initial_stock']);
    }
}
