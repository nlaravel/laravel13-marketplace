<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\PaymentSucceeded;
use App\Jobs\ProcessSuccessfulPayment;
use Illuminate\Contracts\Queue\ShouldQueue;

class QueueSuccessfulPaymentProcessing implements ShouldQueue
{
    public function handle(PaymentSucceeded $event): void
    {
        ProcessSuccessfulPayment::dispatch($event->payment->id);
    }
}
