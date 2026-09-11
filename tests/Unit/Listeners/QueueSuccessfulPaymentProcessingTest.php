<?php

declare(strict_types=1);

namespace Tests\Unit\Listeners;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Events\PaymentSucceeded;
use App\Jobs\ProcessSuccessfulPayment;
use App\Listeners\QueueSuccessfulPaymentProcessing;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class QueueSuccessfulPaymentProcessingTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_queues_successful_payment_processing_job(): void
    {
        Queue::fake();

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

        $event = new PaymentSucceeded($payment);

        $listener = new QueueSuccessfulPaymentProcessing;

        $listener->handle($event);

        Queue::assertPushed(ProcessSuccessfulPayment::class, fn (ProcessSuccessfulPayment $job): bool => $job->paymentId === $payment->id);
    }
}
