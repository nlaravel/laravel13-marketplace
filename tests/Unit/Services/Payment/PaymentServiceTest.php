<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Payment;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\SellerOrderStatus;
use App\Events\PaymentSucceeded;
use App\Exceptions\PaymentException;
use App\Models\Order;
use App\Models\Payment;
use App\Models\SellerOrder;
use App\Services\Payment\Contracts\PaymentGateway;
use App\Services\Payment\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Mockery;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_payment_for_pending_order(): void
    {
        $order = Order::factory()->create([
            'status' => OrderStatus::PENDING,
            'total_amount' => 150.00,
            'currency' => 'USD',
        ]);

        $gateway = Mockery::mock(PaymentGateway::class);

        $gateway->shouldReceive('create')
            ->once()
            ->andReturnUsing(function (Payment $payment): Payment {
                $payment->update([
                    'status' => PaymentStatus::SUCCEEDED,
                    'transaction_id' => 'TEST-TRANSACTION',
                    'provider_payment_id' => 'test_payment_id',
                    'provider_reference' => 'TEST-REFERENCE',
                    'paid_at' => now(),
                ]);

                return $payment->refresh();
            });

        $service = new PaymentService($gateway);

        $payment = $service->create($order, PaymentMethod::CARD);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'order_id' => $order->id,
            'provider' => 'fake',
            'method' => PaymentMethod::CARD->value,
            'status' => PaymentStatus::SUCCEEDED->value,
            'amount' => '150.00',
            'currency' => 'USD',
            'transaction_id' => 'TEST-TRANSACTION',
        ]);
    }

    public function test_it_returns_existing_active_payment(): void
    {
        $order = Order::factory()->create([
            'status' => OrderStatus::PENDING,
        ]);

        $existingPayment = Payment::create([
            'order_id' => $order->id,
            'payment_number' => 'PAY-EXISTING',
            'provider' => 'fake',
            'method' => PaymentMethod::CARD,
            'status' => PaymentStatus::PENDING,
            'amount' => $order->total_amount,
            'currency' => $order->currency,
        ]);

        $gateway = Mockery::mock(PaymentGateway::class);

        $gateway->shouldNotReceive('create');

        $service = new PaymentService($gateway);

        $payment = $service->create($order, PaymentMethod::CARD);

        $this->assertSame($existingPayment->id, $payment->id);
    }

    public function test_it_rejects_payment_for_non_pending_order(): void
    {
        $order = Order::factory()->create([
            'status' => OrderStatus::CONFIRMED,
        ]);

        $gateway = Mockery::mock(PaymentGateway::class);
        $gateway->shouldNotReceive('create');

        $service = new PaymentService($gateway);

        $this->expectException(PaymentException::class);

        try {
            $service->create($order, PaymentMethod::CARD);
        } finally {
            $this->assertDatabaseCount('payments', 0);
        }
    }

    public function test_it_checks_payment_through_gateway(): void
    {
        $order = Order::factory()->create();

        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_number' => 'PAY-CHECK',
            'provider' => 'fake',
            'method' => PaymentMethod::CARD,
            'status' => PaymentStatus::PENDING,
            'amount' => $order->total_amount,
            'currency' => $order->currency,
        ]);

        $gateway = Mockery::mock(PaymentGateway::class);

        $gateway->shouldReceive('check')
            ->once()
            ->with(Mockery::on(fn (Payment $value): bool => $value->id === $payment->id))
            ->andReturn($payment);

        $service = new PaymentService($gateway);

        $result = $service->check($payment);

        $this->assertSame($payment->id, $result->id);
    }

    public function test_it_refunds_successful_payment(): void
    {
        $order = Order::factory()->create();

        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_number' => 'PAY-REFUND',
            'provider' => 'fake',
            'method' => PaymentMethod::CARD,
            'status' => PaymentStatus::SUCCEEDED,
            'amount' => $order->total_amount,
            'currency' => $order->currency,
            'paid_at' => now(),
        ]);

        $gateway = Mockery::mock(PaymentGateway::class);

        $gateway->shouldReceive('refund')
            ->once()
            ->with(Mockery::on(fn (Payment $value): bool => $value->id === $payment->id))
            ->andReturnUsing(function (Payment $value): Payment {
                $value->update([
                    'status' => PaymentStatus::REFUNDED,
                    'refunded_at' => now(),
                ]);

                return $value->refresh();
            });

        $service = new PaymentService($gateway);

        $result = $service->refund($payment);

        $this->assertSame(PaymentStatus::REFUNDED, $result->status);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => PaymentStatus::REFUNDED->value,
        ]);
    }

    public function test_it_rejects_refund_for_non_successful_payment(): void
    {
        $order = Order::factory()->create();

        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_number' => 'PAY-NO-REFUND',
            'provider' => 'fake',
            'method' => PaymentMethod::CARD,
            'status' => PaymentStatus::PENDING,
            'amount' => $order->total_amount,
            'currency' => $order->currency,
        ]);

        $gateway = Mockery::mock(PaymentGateway::class);
        $gateway->shouldNotReceive('refund');

        $service = new PaymentService($gateway);

        $this->expectException(PaymentException::class);

        try {
            $service->refund($payment);
        } finally {
            $this->assertDatabaseHas('payments', [
                'id' => $payment->id,
                'status' => PaymentStatus::PENDING->value,
            ]);
        }
    }

    public function test_it_confirms_order_from_successful_payment(): void
    {
        $order = Order::factory()->create([
            'status' => OrderStatus::PENDING,
        ]);

        $sellerOrder = SellerOrder::factory()->create([
            'order_id' => $order->id,
            'status' => SellerOrderStatus::PENDING,
        ]);

        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_number' => 'PAY-CONFIRM',
            'provider' => 'fake',
            'method' => PaymentMethod::CARD,
            'status' => PaymentStatus::SUCCEEDED,
            'amount' => $order->total_amount,
            'currency' => $order->currency,
            'paid_at' => now(),
        ]);

        $gateway = Mockery::mock(PaymentGateway::class);

        $service = new PaymentService($gateway);

        $result = $service->confirmOrderFromPayment($payment);

        $this->assertSame($order->id, $result->id);
        $this->assertSame(OrderStatus::CONFIRMED, $result->status);
        $this->assertNotNull($result->confirmed_at);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::CONFIRMED->value,
        ]);

        $this->assertDatabaseHas('seller_orders', [
            'id' => $sellerOrder->id,
            'status' => SellerOrderStatus::CONFIRMED->value,
        ]);
    }

    public function test_it_rejects_confirmation_for_unsuccessful_payment(): void
    {
        $order = Order::factory()->create([
            'status' => OrderStatus::PENDING,
        ]);

        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_number' => 'PAY-FAILED',
            'provider' => 'fake',
            'method' => PaymentMethod::CARD,
            'status' => PaymentStatus::FAILED,
            'amount' => $order->total_amount,
            'currency' => $order->currency,
        ]);

        $gateway = Mockery::mock(PaymentGateway::class);

        $service = new PaymentService($gateway);

        $this->expectException(PaymentException::class);

        try {
            $service->confirmOrderFromPayment($payment);
        } finally {
            $this->assertDatabaseHas('orders', [
                'id' => $order->id,
                'status' => OrderStatus::PENDING->value,
            ]);
        }
    }

    public function test_it_rejects_confirmation_for_non_pending_order(): void
    {
        $order = Order::factory()->create([
            'status' => OrderStatus::CONFIRMED,
        ]);

        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_number' => 'PAY-ALREADY-CONFIRMED',
            'provider' => 'fake',
            'method' => PaymentMethod::CARD,
            'status' => PaymentStatus::SUCCEEDED,
            'amount' => $order->total_amount,
            'currency' => $order->currency,
            'paid_at' => now(),
        ]);

        $gateway = Mockery::mock(PaymentGateway::class);

        $service = new PaymentService($gateway);

        $this->expectException(PaymentException::class);

        try {
            $service->confirmOrderFromPayment($payment);
        } finally {
            $this->assertDatabaseHas('orders', [
                'id' => $order->id,
                'status' => OrderStatus::CONFIRMED->value,
            ]);
        }
    }

    public function test_successful_payment_dispatches_event(): void
    {
        Event::fake([
            PaymentSucceeded::class,
        ]);

        $order = Order::factory()->create([
            'status' => OrderStatus::PENDING,
        ]);

        $gateway = Mockery::mock(PaymentGateway::class);

        $gateway->shouldReceive('create')
            ->once()
            ->andReturnUsing(function (Payment $payment): Payment {
                $payment->update([
                    'status' => PaymentStatus::SUCCEEDED,
                    'transaction_id' => 'TEST-TRANSACTION',
                    'provider_payment_id' => 'test_payment_id',
                    'provider_reference' => 'TEST-REFERENCE',
                    'paid_at' => now(),
                ]);

                return $payment->refresh();
            });

        $service = new PaymentService($gateway);

        $payment = $service->create($order, PaymentMethod::CARD);

        Event::assertDispatched(PaymentSucceeded::class, fn (PaymentSucceeded $event): bool => $event->payment->id === $payment->id);
    }
}
