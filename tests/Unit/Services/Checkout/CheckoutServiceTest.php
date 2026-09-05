<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Checkout;

use App\Enums\CartStatus;
use App\Enums\InventoryTransactionType;
use App\Enums\OrderAddressType;
use App\Enums\OrderStatus;
use App\Enums\SellerOrderStatus;
use App\Exceptions\DomainException;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SellerOrder;
use App\Models\Store;
use App\Models\User;
use App\Services\Checkout\CheckoutService;
use App\Services\Inventory\InventoryReservationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_completes_checkout_successfully(): void
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

        $item = $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 3,
        ]);

        $service = new CheckoutService(new InventoryReservationService());

        $order = $service->checkout($customer);

        $this->assertInstanceOf(Order::class, $order);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'customer_id' => $customer->id,
            'status' => OrderStatus::PENDING->value,
            'subtotal' => '150.00',
            'total_amount' => '150.00',
            'currency' => 'USD',
        ]);

        $this->assertDatabaseHas('seller_orders', [
            'order_id' => $order->id,
            'store_id' => $store->id,
            'status' => SellerOrderStatus::PENDING->value,
            'subtotal' => '150.00',
            'total_amount' => '150.00',
        ]);

        $sellerOrder = SellerOrder::query()
            ->where('order_id', $order->id)
            ->firstOrFail();

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'seller_order_id' => $sellerOrder->id,
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'quantity' => 3,
            'unit_price' => '50.00',
            'total_amount' => '150.00',
        ]);

        $this->assertDatabaseHas('order_addresses', [
            'order_id' => $order->id,
            'type' => OrderAddressType::SHIPPING->value,
        ]);

        $this->assertDatabaseHas('inventories', [
            'id' => $inventory->id,
            'quantity' => 10,
            'reserved_quantity' => 5,
        ]);

        $this->assertDatabaseHas('inventory_transactions', [
            'inventory_id' => $inventory->id,
            'type' => InventoryTransactionType::RESERVATION->value,
            'quantity' => 3,
            'quantity_before' => 2,
            'quantity_after' => 5,
            'reference_type' => Order::class,
            'reference_id' => $order->id,
        ]);

        $this->assertDatabaseMissing('cart_items', [
            'id' => $item->id,
        ]);

        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_it_rejects_checkout_when_cart_is_empty(): void
    {
        $customer = User::factory()->create();

        Cart::factory()->create([
            'user_id' => $customer->id,
            'status' => CartStatus::ACTIVE,
        ]);

        $service = new CheckoutService(new InventoryReservationService());

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Cart is empty.');

        try {
            $service->checkout($customer);
        } finally {
            $this->assertDatabaseCount('orders', 0);
        }
    }

    public function test_it_rejects_checkout_when_stock_is_insufficient(): void
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

        $item = $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 3,
        ]);

        $service = new CheckoutService(new InventoryReservationService());

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Insufficient stock for variant {$variant->id}.");

        try {
            $service->checkout($customer);
        } finally {
            $this->assertDatabaseCount('orders', 0);

            $this->assertDatabaseCount('seller_orders', 0);

            $this->assertDatabaseCount('order_items', 0);

            $this->assertDatabaseCount('order_addresses', 0);

            $this->assertDatabaseCount('inventory_transactions', 0);

            $this->assertDatabaseHas('inventories', [
                'id' => $inventory->id,
                'reserved_quantity' => 8,
            ]);

            $this->assertDatabaseHas('cart_items', [
                'id' => $item->id,
                'quantity' => 3,
            ]);
        }
    }

    public function test_it_rolls_back_checkout_when_default_address_is_missing(): void
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

        $item = $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 3,
        ]);

        $service = new CheckoutService(new InventoryReservationService());

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Default address not found.');

        try {
            $service->checkout($customer);
        } finally {
            $this->assertDatabaseCount('orders', 0);

            $this->assertDatabaseCount('seller_orders', 0);

            $this->assertDatabaseCount('order_items', 0);

            $this->assertDatabaseCount('order_addresses', 0);

            $this->assertDatabaseCount('inventory_transactions', 0);

            $this->assertDatabaseHas('inventories', [
                'id' => $inventory->id,
                'reserved_quantity' => 2,
            ]);

            $this->assertDatabaseHas('cart_items', [
                'id' => $item->id,
                'quantity' => 3,
            ]);
        }
    }

    public function test_it_creates_multiple_seller_orders_for_multiple_stores(): void
    {
        $customer = User::factory()->create();

        Address::factory()->create([
            'user_id' => $customer->id,
            'is_default' => true,
        ]);

        $storeOne = Store::factory()->create();
        $storeTwo = Store::factory()->create();

        $productOne = Product::factory()->create([
            'store_id' => $storeOne->id,
        ]);

        $productTwo = Product::factory()->create([
            'store_id' => $storeTwo->id,
        ]);

        $variantOne = ProductVariant::factory()->create([
            'product_id' => $productOne->id,
            'price' => 20,
        ]);

        $variantTwo = ProductVariant::factory()->create([
            'product_id' => $productTwo->id,
            'price' => 30,
        ]);

        $inventoryOne = Inventory::factory()->create([
            'product_variant_id' => $variantOne->id,
            'quantity' => 10,
            'reserved_quantity' => 0,
        ]);

        $inventoryTwo = Inventory::factory()->create([
            'product_variant_id' => $variantTwo->id,
            'quantity' => 10,
            'reserved_quantity' => 0,
        ]);

        $cart = Cart::factory()->create([
            'user_id' => $customer->id,
            'status' => CartStatus::ACTIVE,
        ]);

        $cart->items()->create([
            'product_variant_id' => $variantOne->id,
            'quantity' => 2,
        ]);

        $cart->items()->create([
            'product_variant_id' => $variantTwo->id,
            'quantity' => 3,
        ]);

        $service = new CheckoutService(new InventoryReservationService());

        $order = $service->checkout($customer);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'subtotal' => '130.00',
            'total_amount' => '130.00',
        ]);

        $this->assertDatabaseCount('seller_orders', 2);

        $this->assertDatabaseHas('seller_orders', [
            'order_id' => $order->id,
            'store_id' => $storeOne->id,
            'subtotal' => '40.00',
            'total_amount' => '40.00',
        ]);

        $this->assertDatabaseHas('seller_orders', [
            'order_id' => $order->id,
            'store_id' => $storeTwo->id,
            'subtotal' => '90.00',
            'total_amount' => '90.00',
        ]);

        $this->assertDatabaseCount('order_items', 2);

        $this->assertDatabaseHas('inventories', [
            'id' => $inventoryOne->id,
            'reserved_quantity' => 2,
        ]);

        $this->assertDatabaseHas('inventories', [
            'id' => $inventoryTwo->id,
            'reserved_quantity' => 3,
        ]);

        $this->assertDatabaseCount('inventory_transactions', 2);

        $this->assertDatabaseCount('cart_items', 0);
    }
}
