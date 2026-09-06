<?php

declare(strict_types=1);

namespace Tests\Feature\Customer\Order;

use App\Enums\OrderStatus;
use App\Models\Address;
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
}