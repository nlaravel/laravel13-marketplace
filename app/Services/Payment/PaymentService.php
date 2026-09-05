<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\SellerOrderStatus;
use App\Exceptions\DomainException;
use App\Models\Order;
use App\Models\Payment;
use App\Services\Payment\Contracts\PaymentGateway;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentService
{
    public function __construct(private readonly PaymentGateway $gateway) {}

    public function create(Order $order, PaymentMethod $method): Payment
    {
        if ($order->status !== OrderStatus::PENDING) {
            throw new DomainException('Payment can only be created for a pending order.');
        }

        $existingPayment = $order->payments()
            ->whereIn('status', [
                PaymentStatus::PENDING,
                PaymentStatus::PROCESSING,
                PaymentStatus::SUCCEEDED,
            ])
            ->latest()
            ->first();

        if ($existingPayment) {
            return $existingPayment;
        }

        return DB::transaction(function () use ($order, $method): Payment {
            $payment = $order->payments()->create([
                'payment_number' => 'PAY-'
                    . now()->format('YmdHis')
                    . '-'
                    . Str::upper(Str::random(4)),
                'provider' => 'fake',
                'method' => $method,
                'status' => PaymentStatus::PENDING,
                'amount' => $order->total_amount,
                'currency' => $order->currency,
            ]);

            return $this->gateway->create($payment);
        });
    }

    public function check(Payment $payment): Payment
    {
        return $this->gateway->check($payment);
    }

    public function refund(Payment $payment): Payment
    {
        if ($payment->status !== PaymentStatus::SUCCEEDED) {
            throw new DomainException('Only successful payments can be refunded.');
        }

        return DB::transaction(fn(): Payment => $this->gateway->refund($payment));
    }

    public function confirmOrderFromPayment(Payment $payment): Order
    {
        return DB::transaction(function () use ($payment): Order {
            $payment->refresh();

            if ($payment->status !== PaymentStatus::SUCCEEDED) {
                throw new DomainException('Only successful payments can confirm an order.');
            }

            // Lock the order row so concurrent payment confirmations cannot
            // confirm the same pending order at the same time.
            $order = $payment->order()
                ->lockForUpdate()
                ->firstOrFail();

            if ($order->status !== OrderStatus::PENDING) {
                throw new DomainException('Only pending orders can be confirmed.');
            }

            $order->update([
                'status' => OrderStatus::CONFIRMED,
                'confirmed_at' => now(),
            ]);

            $order->sellerOrders()->update([
                'status' => SellerOrderStatus::CONFIRMED,
            ]);

            return $order->refresh();
        });
    }
}
