<?php

declare(strict_types=1);

namespace Tests\Feature\Customer\Order;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_create_payment(): void
    {
        $order = Order::factory()->create([
            'status' => OrderStatus::PENDING,
        ]);

        $this
            ->postJson("/api/v1/customer/orders/{$order->id}/payment", [
                'method' => PaymentMethod::CARD->value,
            ])
            ->assertUnauthorized();
    }

    public function test_customer_can_create_payment_for_their_pending_order(): void
    {
        $customer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::PENDING,
            'total_amount' => 150,
            'currency' => 'USD',
        ]);

        $response = $this
            ->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/customer/orders/{$order->id}/payment", [
                'method' => PaymentMethod::CARD->value,
            ]);

        $response
            ->assertSuccessful()
            ->assertJsonPath('data.method', PaymentMethod::CARD->value)
            ->assertJsonPath('data.status', PaymentStatus::SUCCEEDED->value)
            ->assertJsonPath('data.amount', '150.00')
        ->assertJsonPath('data.currency', 'USD');

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'method' => PaymentMethod::CARD->value,
            'status' => PaymentStatus::SUCCEEDED->value,
            'amount' => '150.00',
            'currency' => 'USD',
        ]);
    }

    public function test_customer_cannot_create_payment_for_another_customers_order(): void
    {
        $customer = User::factory()->create();
        $anotherCustomer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $anotherCustomer->id,
            'status' => OrderStatus::PENDING,
        ]);

        $this
            ->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/customer/orders/{$order->id}/payment", [
                'method' => PaymentMethod::CARD->value,
            ])
            ->assertNotFound();

        $this->assertDatabaseMissing('payments', [
            'order_id' => $order->id,
        ]);
    }

    public function test_customer_cannot_create_payment_for_non_pending_order(): void
    {
        $customer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::CONFIRMED,
        ]);

        $this
            ->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/customer/orders/{$order->id}/payment", [
                'method' => PaymentMethod::CARD->value,
            ])
            ->assertUnprocessable()
        ->assertJson([
            'message' => 'Payment can only be created for a pending order.',
        ]);

        $this->assertDatabaseMissing('payments', [
            'order_id' => $order->id,
        ]);
    }

    public function test_payment_method_is_required(): void
    {
        $customer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::PENDING,
        ]);

        $this
            ->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/customer/orders/{$order->id}/payment")
            ->assertUnprocessable()
            ->assertJsonValidationErrors('method');
    }

    public function test_payment_method_must_be_valid(): void
    {
        $customer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::PENDING,
        ]);

        $this
            ->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/customer/orders/{$order->id}/payment", [
                'method' => 'invalid_method',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('method');

        $this->assertDatabaseMissing('payments', [
            'order_id' => $order->id,
        ]);
    }

    public function test_customer_gets_existing_active_payment_instead_of_creating_another_one(): void
    {
        $customer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::PENDING,
            'total_amount' => 200,
            'currency' => 'USD',
        ]);

        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_number' => 'PAY-EXISTING-001',
            'provider' => 'fake',
            'method' => PaymentMethod::CARD,
            'status' => PaymentStatus::SUCCEEDED,
            'amount' => 200,
            'currency' => 'USD',
            'transaction_id' => 'FAKE-TRANSACTION-001',
        ]);

        $response = $this
            ->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/customer/orders/{$order->id}/payment", [
                'method' => PaymentMethod::WALLET->value,
            ]);

        $response
            ->assertSuccessful()
            ->assertJsonPath('data.id', $payment->id)
            ->assertJsonPath('data.payment_number', 'PAY-EXISTING-001')
            ->assertJsonPath('data.method', PaymentMethod::CARD->value);

        $this->assertDatabaseCount('payments', 1);
    }
}
