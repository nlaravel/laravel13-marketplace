<?php

namespace App\Services\Orders;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\SellerOrderStatus;
use App\Models\Order;
use App\Services\Inventory\InventoryReservationService;
use App\Services\Payment\PaymentService;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class OrderCancellationService
{
    public function __construct(
        private readonly InventoryReservationService $inventoryReservationService,
        private readonly PaymentService $paymentService,
    ) {}

    public function cancel(Order $order): Order
    {
        return DB::transaction(function () use ($order) {
            $order = Order::query()
                ->lockForUpdate()
                ->findOrFail($order->id);

            if (! $this->canCancel($order)) {
                throw new InvalidArgumentException(
                    "Order {$order->id} cannot be cancelled."
                );
            }

            $payment = $order->payments()
                ->where('status', PaymentStatus::SUCCEEDED)
                ->latest()
                ->first();

            if ($payment) {
                $this->paymentService->refund($payment);
            }

            $this->inventoryReservationService->release($order);

            $order->sellerOrders()->update([
                'status' => SellerOrderStatus::CANCELLED,
            ]);

            $order->update([
                'status' => OrderStatus::CANCELLED,
                'cancelled_at' => now(),
            ]);

            return $order->refresh();
        });
    }

    private function canCancel(Order $order): bool
    {
        return in_array(
            $order->status,
            [
                OrderStatus::PENDING,
                OrderStatus::CONFIRMED,
            ],
            true
        );
    }
}
