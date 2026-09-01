<?php

namespace App\Services\Orders;

use App\Enums\OrderStatus;
use App\Enums\SellerOrderStatus;
use App\Models\Order;
use App\Models\SellerOrder;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class SellerOrderStatusService
{
    public function __construct(
        private OrderStatusService $orderStatusService
    ) {
    }

/**
 * Confirm a pending seller order.
 */
public function confirm(SellerOrder $sellerOrder): SellerOrder
{
    return $this->transition(
        $sellerOrder,
        SellerOrderStatus::PENDING,
        SellerOrderStatus::CONFIRMED
    );
}

/**
 * Start processing a confirmed seller order.
 */
public function process(SellerOrder $sellerOrder): SellerOrder
{
    return $this->transition(
        $sellerOrder,
        SellerOrderStatus::CONFIRMED,
        SellerOrderStatus::PROCESSING
    );
}

/**
 * Mark a processing seller order as ready for shipping.
 */
public function markReadyForShipping(
    SellerOrder $sellerOrder
): SellerOrder {
    return $this->transition(
        $sellerOrder,
        SellerOrderStatus::PROCESSING,
        SellerOrderStatus::READY_FOR_SHIPPING
    );
}

/**
 * Mark a seller order as shipped.
 */
public function markShipped(SellerOrder $sellerOrder): SellerOrder
{
    return $this->transition(
        $sellerOrder,
        SellerOrderStatus::READY_FOR_SHIPPING,
        SellerOrderStatus::SHIPPED
    );
}

/**
 * Mark a shipped seller order as delivered.
 */
public function markDelivered(SellerOrder $sellerOrder): SellerOrder
{
    return DB::transaction(function () use ($sellerOrder) {
        $sellerOrder->refresh();

        if ($sellerOrder->status !== SellerOrderStatus::SHIPPED) {
            throw new InvalidArgumentException(
                'Only shipped seller orders can be marked as delivered.'
            );
        }

        $sellerOrder->update([
            'status' => SellerOrderStatus::DELIVERED,
        ]);

        $sellerOrder->refresh();

        $this->syncOrderStatus($sellerOrder->order_id);

        return $sellerOrder;
    });
}

/**
 * Cancel a seller order.
 */
public function cancel(SellerOrder $sellerOrder): SellerOrder
{
    return DB::transaction(function () use ($sellerOrder) {
        $sellerOrder->refresh();

        if (in_array($sellerOrder->status, [
            SellerOrderStatus::DELIVERED,
            SellerOrderStatus::COMPLETED,
            SellerOrderStatus::CANCELLED,
        ], true)) {
            throw new InvalidArgumentException(
                'This seller order cannot be cancelled.'
            );
        }

        $sellerOrder->update([
            'status' => SellerOrderStatus::CANCELLED,
        ]);

        $sellerOrder->refresh();

        return $sellerOrder;
    });
}

/**
 * Complete a delivered seller order.
 */
public function complete(SellerOrder $sellerOrder): SellerOrder
{
    return $this->transition(
        $sellerOrder,
        SellerOrderStatus::DELIVERED,
        SellerOrderStatus::COMPLETED
    );
}

/**
 * Perform a controlled seller order status transition.
 */
private function transition(
    SellerOrder $sellerOrder,
    SellerOrderStatus $from,
    SellerOrderStatus $to
): SellerOrder {
    return DB::transaction(function () use (
        $sellerOrder,
        $from,
        $to
    ) {
        $sellerOrder->refresh();

        if ($sellerOrder->status !== $from) {
            throw new InvalidArgumentException(
                "Cannot change seller order status from "
                . "{$sellerOrder->status->value} to {$to->value}."
            );
        }

        $sellerOrder->update([
            'status' => $to,
        ]);

        return $sellerOrder->refresh();
    });
}

/**
 * Synchronize the parent order status with its seller orders.
 */
private function syncOrderStatus(int $orderId): void
{
    $order = Order::query()
        ->with('sellerOrders')
        ->findOrFail($orderId);

    $sellerOrders = $order->sellerOrders;

    if ($sellerOrders->isEmpty()) {
        return;
    }

    $statuses = $sellerOrders->pluck('status');

    /*
     * All seller orders are delivered.
     */
    if ($statuses->every(
        fn ($status) => $status === SellerOrderStatus::DELIVERED
        )) {
    if ($order->status !== OrderStatus::DELIVERED) {
        $this->orderStatusService->changeStatus(
            $order,
            OrderStatus::DELIVERED
        );
    }

    return;
}

        /*
         * At least one seller order is delivered.
         */
        if ($statuses->contains(
            fn ($status) => $status === SellerOrderStatus::DELIVERED
        )) {
    if ($order->status !== OrderStatus::PARTIALLY_DELIVERED) {
        $this->orderStatusService->changeStatus(
            $order,
            OrderStatus::PARTIALLY_DELIVERED
        );
    }
}
    }
}
