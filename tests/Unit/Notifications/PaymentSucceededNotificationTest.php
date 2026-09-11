<?php

declare(strict_types=1);

namespace Tests\Unit\Notifications;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Notifications\PaymentSucceededNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

class PaymentSucceededNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_contains_correct_channels_and_data(): void
    {
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

        $notification = new PaymentSucceededNotification($payment);

        $this->assertSame(['database', 'mail'], $notification->via($order->customer));

        $this->assertSame([
            'type' => 'payment_succeeded',
            'payment_id' => $payment->id,
            'payment_number' => $payment->payment_number,
            'order_id' => $payment->order_id,
            'amount' => $payment->amount,
            'currency' => $payment->currency,
        ], $notification->toArray($order->customer));
    }

    public function test_it_contains_correct_mail_content(): void
    {
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

        $notification = new PaymentSucceededNotification($payment);

        $mail = $notification->toMail($order->customer);

        $this->assertInstanceOf(MailMessage::class, $mail);

        $this->assertSame('Payment Successful', $mail->subject);

        $this->assertSame('Hello!', $mail->greeting);

        $this->assertSame([
            "Your payment {$payment->payment_number} was completed successfully.",
            "Amount: {$payment->amount} {$payment->currency}",
            'Thank you for your order.',
        ], $mail->introLines);

    }

    public function test_it_stores_database_notification(): void
    {
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

        $notification = new PaymentSucceededNotification($payment);

        $order->customer->notifyNow($notification);

        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => $order->customer->getMorphClass(),
            'notifiable_id' => $order->customer->id,
            'type' => PaymentSucceededNotification::class,
        ]);
    }
}
