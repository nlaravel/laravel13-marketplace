<?php

declare(strict_types=1);

namespace Tests\Feature\Customer\Order;

use App\Exceptions\InventoryException;
use App\Enums\InventoryTransactionType;
use App\Enums\OrderStatus;
use App\Models\Address;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SellerOrder;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_view_orders(): void
    {
        $response = $this->getJson('/api/v1/customer/orders');

        $response->assertUnauthorized();
    }

    public function test_customer_can_view_their_orders(): void
    {
        $customer = User::factory()->create();

        Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::PENDING,
            'subtotal' => 150,
            'total_amount' => 150,
        ]);

        Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::COMPLETED,
            'subtotal' => 200,
            'total_amount' => 200,
        ]);

        $response = $this
            ->actingAs($customer, 'sanctum')
            ->getJson('/api/v1/customer/orders');

        $response->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
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
                ],
                'links',
                'meta',
            ]);

        $response->assertJsonCount(2, 'data');
    }

    public function test_customer_can_view_their_order(): void
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

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::PENDING,
            'subtotal' => 150,
            'total_amount' => 150,
        ]);

        $sellerOrder = SellerOrder::factory()->create([
            'order_id' => $order->id,
            'store_id' => $store->id,
            'status' => 'pending',
            'subtotal' => 150,
            'total_amount' => 150,
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'seller_order_id' => $sellerOrder->id,
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'product_name' => $product->name,
            'sku' => $variant->sku,
            'quantity' => 3,
            'unit_price' => 50,
            'total_amount' => 150,
        ]);

        $address = Address::factory()->create([
            'user_id' => $customer->id,
            'is_default' => true,
        ]);

        OrderAddress::factory()->create([
            'order_id' => $order->id,
            'recipient_name' => $address->recipient_name,
            'phone' => $address->phone,
            'country' => $address->country,
            'city' => $address->city,
            'area' => $address->area,
            'street' => $address->street,
            'building' => $address->building,
            'apartment' => $address->apartment,
            'address_line' => $address->address_line,
        ]);

        $response = $this
            ->actingAs($customer, 'sanctum')
            ->getJson("/api/v1/customer/orders/{$order->id}");

        $response->assertSuccessful()
            ->assertJsonPath('data.id', $order->id)
            ->assertJsonPath('data.order_number', $order->order_number)
            ->assertJsonPath('data.status', OrderStatus::PENDING->value)
            ->assertJsonPath('data.subtotal', '150.00')
        ->assertJsonPath('data.total_amount', '150.00');

        $response->assertJsonStructure([
            'data' => [
                'items',
                'seller_orders',
                'addresses',
            ],
        ]);
    }

    public function test_customer_cannot_view_another_customers_order(): void
    {
        $customer = User::factory()->create();
        $anotherCustomer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $anotherCustomer->id,
        ]);

        $this
            ->actingAs($customer, 'sanctum')
            ->getJson("/api/v1/customer/orders/{$order->id}")
            ->assertNotFound();
    }

    public function test_guest_cannot_cancel_order(): void
    {
        $order = Order::factory()->create([
            'status' => OrderStatus::PENDING,
        ]);

        $this
            ->postJson("/api/v1/customer/orders/{$order->id}/cancel")
            ->assertUnauthorized();
    }

    public function test_customer_can_cancel_pending_order(): void
    {
        $customer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::PENDING,
        ]);

        $response = $this
            ->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/customer/orders/{$order->id}/cancel");

        $response
            ->assertSuccessful()
            ->assertJsonPath('data.id', $order->id)
            ->assertJsonPath('data.status', OrderStatus::CANCELLED->value);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::CANCELLED->value,
        ]);

        $this->assertNotNull(Order::query()->findOrFail($order->id)->cancelled_at);
    }

    public function test_customer_can_cancel_confirmed_order(): void
    {
        $customer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::CONFIRMED,
        ]);

        $this
            ->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/customer/orders/{$order->id}/cancel")
            ->assertSuccessful()
            ->assertJsonPath('data.status', OrderStatus::CANCELLED->value);
    }

    public function test_customer_cannot_cancel_another_customers_order(): void
    {
        $customer = User::factory()->create();
        $anotherCustomer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $anotherCustomer->id,
            'status' => OrderStatus::PENDING,
        ]);

        $this
            ->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/customer/orders/{$order->id}/cancel")
            ->assertNotFound();
    }

    public function test_customer_cannot_cancel_processing_order(): void
    {
        $customer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::PROCESSING,
        ]);

        $this
            ->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/customer/orders/{$order->id}/cancel")
            ->assertUnprocessable()
            ->assertJson([
                'message' => 'Order cannot be cancelled.',
            ]);
    }

    #[DataProvider('nonCancellableStatuses')]
    public function test_customer_cannot_cancel_non_cancellable_order(OrderStatus $status): void
    {
        $customer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => $status,
        ]);

        $this
            ->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/customer/orders/{$order->id}/cancel")
            ->assertUnprocessable()
        ->assertJson([
            'message' => 'Order cannot be cancelled.',
        ]);
    }

    public static function nonCancellableStatuses(): array
    {
        return [
            'processing' => [OrderStatus::PROCESSING],
            'partially shipped' => [OrderStatus::PARTIALLY_SHIPPED],
            'shipped' => [OrderStatus::SHIPPED],
            'partially delivered' => [OrderStatus::PARTIALLY_DELIVERED],
            'delivered' => [OrderStatus::DELIVERED],
            'cancelled' => [OrderStatus::CANCELLED],
            'completed' => [OrderStatus::COMPLETED],
        ];
    }

    public function test_customer_can_cancel_order_and_release_inventory(): void
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
            'quantity' => 100,
            'reserved_quantity' => 3,
        ]);

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::PENDING,
            'subtotal' => 150,
            'total_amount' => 150,
        ]);

        $sellerOrder = SellerOrder::factory()->create([
            'order_id' => $order->id,
            'store_id' => $store->id,
            'status' => 'pending',
            'subtotal' => 150,
            'total_amount' => 150,
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'seller_order_id' => $sellerOrder->id,
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'product_name' => $product->name,
            'sku' => $variant->sku,
            'quantity' => 3,
            'unit_price' => 50,
            'total_amount' => 150,
        ]);

        $response = $this
            ->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/customer/orders/{$order->id}/cancel");

        $response
            ->assertSuccessful()
            ->assertJsonPath('data.status', OrderStatus::CANCELLED->value);

        $this->assertDatabaseHas('inventories', [
            'id' => $inventory->id,
            'reserved_quantity' => 0,
        ]);

        $this->assertDatabaseHas('inventory_transactions', [
            'inventory_id' => $inventory->id,
            'type' => InventoryTransactionType::RELEASE->value,
            'quantity' => 3,
            'quantity_before' => 3,
            'quantity_after' => 0,
            'reference_type' => Order::class,
            'reference_id' => $order->id,
            'created_by' => $customer->id,
            'note' => 'Inventory reservation released for order.',
        ]);
    }

    public function test_customer_cancel_order_rolls_back_when_inventory_release_fails(): void
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
            'quantity' => 100,
            'reserved_quantity' => 2,
        ]);

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::PENDING,
            'subtotal' => 150,
            'total_amount' => 150,
            'cancelled_at' => null,
        ]);

        $sellerOrder = SellerOrder::factory()->create([
            'order_id' => $order->id,
            'store_id' => $store->id,
            'status' => 'pending',
            'subtotal' => 150,
            'total_amount' => 150,
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'seller_order_id' => $sellerOrder->id,
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'product_name' => $product->name,
            'sku' => $variant->sku,
            'quantity' => 3,
            'unit_price' => 50,
            'total_amount' => 150,
        ]);

        $response = $this
            ->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/customer/orders/{$order->id}/cancel");

        $response
            ->assertUnprocessable()
            ->assertJson([
                'message' => "Insufficient reserved quantity for variant {$variant->id}.",
            ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::PENDING->value,
            'cancelled_at' => null,
        ]);

        $this->assertDatabaseHas('inventories', [
            'id' => $inventory->id,
            'reserved_quantity' => 2,
        ]);

        $this->assertDatabaseMissing('inventory_transactions', [
            'inventory_id' => $inventory->id,
            'type' => InventoryTransactionType::RELEASE->value,
            'reference_type' => Order::class,
            'reference_id' => $order->id,
        ]);
    }
}
