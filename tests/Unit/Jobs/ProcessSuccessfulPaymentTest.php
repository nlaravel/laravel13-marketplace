<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Jobs\ProcessSuccessfulPayment;
use App\Models\Order;
use App\Models\Payment;
use App\Notifications\PaymentSucceededNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ProcessSuccessfulPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sends_payment_succeeded_notification(): void
    {
        Notification::fake();

        $order = Order::factory()->create();

        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_number' => 'PAY-TEST-001',
            'provider' => 'fake',
            'method' => PaymentMethod::CARD,
            'status' => PaymentStatus::SUCCEEDED,
            'amount' => $order->total_amount,
            'currency' => $order->currency,
            'transaction_id' => 'TEST-TRANSACTION',
            'provider_payment_id' => 'test_payment_id',
            'provider_reference' => 'TEST-REFERENCE',
            'paid_at' => now(),
        ]);

        $job = new ProcessSuccessfulPayment($payment->id);

        $job->handle();

        Notification::assertSentTo($order->customer, PaymentSucceededNotification::class, fn (PaymentSucceededNotification $notification): bool => $notification->payment->id === $payment->id);
    }
}
