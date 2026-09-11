<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Payment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessSuccessfulPayment implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly int $paymentId) {}

    public function handle(): void
    {
        $payment = Payment::find($this->paymentId);

        if ($payment === null) {
            return;
        }

        logger()->info('Successful payment processed asynchronously.', [
            'payment_id' => $payment->id,
            'payment_number' => $payment->payment_number,
            'order_id' => $payment->order_id,
        ]);
    }
}
