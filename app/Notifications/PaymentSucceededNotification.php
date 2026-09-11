<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSucceededNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Payment $payment) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment Successful')
            ->greeting('Hello!')
            ->line("Your payment {$this->payment->payment_number} was completed successfully.")
            ->line("Amount: {$this->payment->amount} {$this->payment->currency}")
            ->line('Thank you for your order.');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'payment_succeeded',
            'payment_id' => $this->payment->id,
            'payment_number' => $this->payment->payment_number,
            'order_id' => $this->payment->order_id,
            'amount' => $this->payment->amount,
            'currency' => $this->payment->currency,
        ];
    }
}
