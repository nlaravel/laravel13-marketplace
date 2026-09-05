<?php

declare(strict_types=1);

namespace Tests\Feature\Customer\Checkout;

use App\Enums\CartStatus;
use App\Enums\OrderStatus;
use App\Exceptions\DomainException;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_checkout(): void
    {
        $response = $this->postJson('/api/v1/customer/checkout');

        $response->assertUnauthorized();
    }

    public function test_customer_can_checkout_successfully(): void
    {
        $customer = User::factory()->create();

        Address::factory()->create([
            'user_id' => $customer->id,
            'is_default' => true,
        ]);

        $store = Store::factory()->create();

        $product = Product::factory()->create([
            'store_id' => $store->id,
        ]);

        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'price' => 50,
        ]);

        $inventory = Inventory::factory()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 10,
            'reserved_quantity' => 2,
        ]);

        $cart = Cart::factory()->create([
            'user_id' => $customer->id,
            'status' => CartStatus::ACTIVE,
        ]);

        $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 3,
        ]);

        $response = $this
            ->actingAs($customer, 'sanctum')
            ->postJson('/api/v1/customer/checkout');

        $response->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'order_number',
                    'status',
                    'subtotal',
                    'shipping_amount',
                    'discount_amount',
                    'tax_amount',
                    'total_amount',
                    'currency',
                    'items',
                    'seller_orders',
                    'addresses',
                    'created_at',
                ],
            ]);

        $response->assertJsonPath('data.status', OrderStatus::PENDING->value);
        $response->assertJsonPath('data.subtotal', '150.00');
        $response->assertJsonPath('data.total_amount', '150.00');
        $response->assertJsonPath('data.currency', 'USD');

        $this->assertDatabaseCount('orders', 1);

        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'status' => OrderStatus::PENDING->value,
            'subtotal' => '150.00',
            'total_amount' => '150.00',
        ]);

        $this->assertDatabaseHas('inventories', [
            'id' => $inventory->id,
            'quantity' => 10,
            'reserved_quantity' => 5,
        ]);

        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_customer_cannot_checkout_with_empty_cart(): void
    {
        $customer = User::factory()->create();

        Cart::factory()->create([
            'user_id' => $customer->id,
            'status' => CartStatus::ACTIVE,
        ]);

        $this->withoutExceptionHandling();

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Cart is empty.');

        $this
            ->actingAs($customer, 'sanctum')
            ->postJson('/api/v1/customer/checkout');
    }

    public function test_customer_cannot_checkout_with_insufficient_stock(): void
    {
        $customer = User::factory()->create();

        Address::factory()->create([
            'user_id' => $customer->id,
            'is_default' => true,
        ]);

        $store = Store::factory()->create();

        $product = Product::factory()->create([
            'store_id' => $store->id,
        ]);

        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'price' => 50,
        ]);

        $inventory = Inventory::factory()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 10,
            'reserved_quantity' => 8,
        ]);

        $cart = Cart::factory()->create([
            'user_id' => $customer->id,
            'status' => CartStatus::ACTIVE,
        ]);

        $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 3,
        ]);

        $this->withoutExceptionHandling();

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            "Insufficient stock for variant {$variant->id}."
        );

        $this
            ->actingAs($customer, 'sanctum')
            ->postJson('/api/v1/customer/checkout');

        $this->assertDatabaseCount('orders', 0);

        $this->assertDatabaseHas('inventories', [
            'id' => $inventory->id,
            'reserved_quantity' => 8,
        ]);
    }

    public function test_customer_cannot_checkout_without_default_address(): void
    {
        $customer = User::factory()->create();

        $store = Store::factory()->create();

        $product = Product::factory()->create([
            'store_id' => $store->id,
        ]);

        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'price' => 50,
        ]);

        $inventory = Inventory::factory()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 10,
            'reserved_quantity' => 2,
        ]);

        $cart = Cart::factory()->create([
            'user_id' => $customer->id,
            'status' => CartStatus::ACTIVE,
        ]);

        $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 3,
        ]);

        $this->withoutExceptionHandling();

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Default address not found.');

        $this
            ->actingAs($customer, 'sanctum')
            ->postJson('/api/v1/customer/checkout');

        $this->assertDatabaseCount('orders', 0);

        $this->assertDatabaseHas('inventories', [
            'id' => $inventory->id,
            'reserved_quantity' => 2,
        ]);
    }
}