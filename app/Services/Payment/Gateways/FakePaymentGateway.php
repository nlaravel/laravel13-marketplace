<?php

namespace App\Services\Payment\Gateways;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Services\Payment\Contracts\PaymentGateway;
use Illuminate\Support\Str;

class FakePaymentGateway implements PaymentGateway
{
    public function create(Payment $payment): Payment
    {
        $payment->update([
            'status' => PaymentStatus::SUCCEEDED,
            'transaction_id' => 'FAKE-' . Str::upper(Str::random(16)),
            'provider_payment_id' => 'fake_' . Str::lower(Str::random(20)),
            'provider_reference' => 'FAKE-REF-' . Str::upper(Str::random(12)),
            'paid_at' => now(),
        ]);

        return $payment->refresh();
    }

    public function check(Payment $payment): Payment
    {
        return $payment->refresh();
    }

    public function refund(Payment $payment): Payment
    {
        $payment->update([
            'status' => PaymentStatus::REFUNDED,
            'refunded_at' => now(),
        ]);

        return $payment->refresh();
    }
}