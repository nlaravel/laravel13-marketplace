<?php

namespace App\Services\Payment\Contracts;

use App\Models\Payment;

interface PaymentGateway
{
    public function create(Payment $payment): Payment;

    public function check(Payment $payment): Payment;

    public function refund(Payment $payment): Payment;
}