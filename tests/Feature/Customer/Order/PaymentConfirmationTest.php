<?php

declare(strict_types=1);

namespace Tests\Feature\Customer\Order;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\SellerOrderStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Models\SellerOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_confirm_payment(): void
    {
        $order = Order::factory()->create([
            'status' => OrderStatus::PENDING,
        ]);

        $this
            ->postJson("/api/v1/customer/orders/{$order->id}/payment/confirm")
            ->assertUnauthorized();
    }

    public function test_customer_can_confirm_order_after_successful_payment(): void
    {
        $customer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::PENDING,
        ]);

        $sellerOrder = SellerOrder::factory()->create([
            'order_id' => $order->id,
            'status' => SellerOrderStatus::PENDING,
        ]);

        Payment::create([
            'order_id' => $order->id,
            'payment_number' => 'PAY-CONFIRM-001',
            'provider' => 'fake',
            'method' => PaymentMethod::CARD,
            'status' => PaymentStatus::SUCCEEDED,
            'amount' => $order->total_amount,
            'currency' => $order->currency,
            'transaction_id' => 'FAKE-CONFIRM-001',
        ]);

        $response = $this
            ->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/customer/orders/{$order->id}/payment/confirm");

        $response
            ->assertSuccessful()
            ->assertJsonPath('data.id', $order->id)
            ->assertJsonPath('data.status', OrderStatus::CONFIRMED->value);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::CONFIRMED->value,
        ]);

        $this->assertDatabaseHas('seller_orders', [
            'id' => $sellerOrder->id,
            'status' => SellerOrderStatus::CONFIRMED->value,
        ]);
    }

    public function test_customer_cannot_confirm_another_customers_order(): void
    {
        $customer = User::factory()->create();
        $anotherCustomer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $anotherCustomer->id,
            'status' => OrderStatus::PENDING,
        ]);

        Payment::create([
            'order_id' => $order->id,
            'payment_number' => 'PAY-CONFIRM-002',
            'provider' => 'fake',
            'method' => PaymentMethod::CARD,
            'status' => PaymentStatus::SUCCEEDED,
            'amount' => $order->total_amount,
            'currency' => $order->currency,
            'transaction_id' => 'FAKE-CONFIRM-002',
        ]);

        $this
            ->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/customer/orders/{$order->id}/payment/confirm")
            ->assertNotFound();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::PENDING->value,
        ]);
    }

    public function test_customer_cannot_confirm_order_without_payment(): void
    {
        $customer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::PENDING,
        ]);

        $this
            ->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/customer/orders/{$order->id}/payment/confirm")
            ->assertUnprocessable()
            ->assertJson([
                'message' => 'Payment not found.',
            ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::PENDING->value,
        ]);
    }

    public function test_customer_cannot_confirm_order_with_unsuccessful_payment(): void
    {
        $customer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::PENDING,
        ]);

        Payment::create([
            'order_id' => $order->id,
            'payment_number' => 'PAY-CONFIRM-003',
            'provider' => 'fake',
            'method' => PaymentMethod::CARD,
            'status' => PaymentStatus::FAILED,
            'amount' => $order->total_amount,
            'currency' => $order->currency,
        ]);

        $this
            ->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/customer/orders/{$order->id}/payment/confirm")
            ->assertUnprocessable()
            ->assertJson([
                'message' => 'Only successful payments can confirm an order.',
            ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::PENDING->value,
        ]);
    }

    public function test_customer_cannot_confirm_non_pending_order(): void
    {
        $customer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::CONFIRMED,
        ]);

        Payment::create([
            'order_id' => $order->id,
            'payment_number' => 'PAY-CONFIRM-004',
            'provider' => 'fake',
            'method' => PaymentMethod::CARD,
            'status' => PaymentStatus::SUCCEEDED,
            'amount' => $order->total_amount,
            'currency' => $order->currency,
        ]);

        $this
            ->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/customer/orders/{$order->id}/payment/confirm")
            ->assertUnprocessable()
            ->assertJson([
                'message' => 'Only pending orders can be confirmed.',
            ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::CONFIRMED->value,
        ]);
    }
}