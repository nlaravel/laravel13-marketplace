<?php

declare(strict_types=1);

namespace Tests\Feature\Customer\Order;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Exceptions\OrderException;
use App\Livewire\Customer\Orders\Show;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_view_order_details(): void
    {
        $order = Order::factory()->create();

        $this->get(route('customer.orders.show', $order->id))
            ->assertRedirect();
    }

    public function test_customer_can_view_own_order_details(): void
    {
        $customer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'order_number' => 'ORD-SHOW-001',
            'status' => OrderStatus::PENDING,
        ]);

        Livewire::actingAs($customer)
            ->test(Show::class, [
                'order' => $order->id,
            ])
            ->assertStatus(200)
            ->assertSee('ORD-SHOW-001');
    }

    public function test_customer_can_pay_for_own_pending_order(): void
    {
        $customer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::PENDING,
            'total_amount' => 899.97,
            'currency' => 'USD',
        ]);

        Livewire::actingAs($customer)
            ->test(Show::class, [
                'order' => $order->id,
            ])
            ->set('paymentMethod', PaymentMethod::CARD->value)
            ->call('pay')
            ->assertDispatched(
                'show-success',
                message: 'Payment completed successfully.'
            );

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'method' => PaymentMethod::CARD->value,
            'status' => PaymentStatus::SUCCEEDED->value,
            'amount' => '899.97',
            'currency' => 'USD',
        ]);
    }

    public function test_customer_can_confirm_order_after_successful_payment(): void
    {
        $customer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::PENDING,
        ]);

        Payment::create([
            'order_id' => $order->id,
            'payment_number' => 'PAY-TEST-001',
            'provider' => 'fake',
            'method' => PaymentMethod::CARD,
            'status' => PaymentStatus::SUCCEEDED,
            'amount' => $order->total_amount,
            'currency' => $order->currency,
        ]);

        Livewire::actingAs($customer)
            ->test(Show::class, [
                'order' => $order->id,
            ])
            ->call('confirmPayment')
            ->assertDispatched(
                'show-success',
                message: 'Order confirmed successfully.'
            );

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::CONFIRMED->value,
        ]);
    }

    public function test_customer_cannot_view_another_users_order(): void
    {
        $customer = User::factory()->create();
        $anotherCustomer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $anotherCustomer->id,
            'order_number' => 'ORD-OTHER-001',
        ]);

        $this->expectException(ModelNotFoundException::class);

        Livewire::actingAs($customer)
            ->test(Show::class, [
                'order' => $order->id,
            ]);
    }

    public function test_customer_can_cancel_own_order(): void
    {
        $customer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::PENDING,
        ]);

        Livewire::actingAs($customer)
            ->test(Show::class, [
                'order' => $order->id,
            ])
            ->call('cancel')
            ->assertDispatched('show-success', message: 'Order cancelled successfully.');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::CANCELLED->value,
        ]);
    }

    public function test_customer_cannot_cancel_processing_order(): void
    {
        $customer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::PROCESSING,
        ]);

        $this->expectException(OrderException::class);

        try {
            Livewire::actingAs($customer)
                ->test(Show::class, [
                    'order' => $order->id,
                ])
                ->call('cancel');
        } finally {
            $this->assertDatabaseHas('orders', [
                'id' => $order->id,
                'status' => OrderStatus::PROCESSING->value,
            ]);
        }
    }

    public function test_customer_cannot_cancel_delivered_order(): void
    {
        $customer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::DELIVERED,
        ]);

        $this->expectException(OrderException::class);

        try {
            Livewire::actingAs($customer)
                ->test(Show::class, [
                    'order' => $order->id,
                ])
                ->call('cancel');
        } finally {
            $this->assertDatabaseHas('orders', [
                'id' => $order->id,
                'status' => OrderStatus::DELIVERED->value,
            ]);
        }
    }
}
