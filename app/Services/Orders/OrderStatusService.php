<?php

namespace App\Services\Orders;

use App\Enums\OrderStatus;
use App\Models\Order;
use InvalidArgumentException;

class OrderStatusService
{
    /**
     * Change the order status while enforcing valid transitions.
     */
    public function changeStatus(Order $order, OrderStatus $newStatus): void
    {
        $currentStatus = $order->status;

        if ($currentStatus === $newStatus) {
            return;
        }

        if (! $this->canTransition($currentStatus, $newStatus)) {
            throw new InvalidArgumentException(
                "Cannot change order status from {$currentStatus->value} to {$newStatus->value}."
            );
        }

        $order->status = $newStatus;

        $this->updateTimestamps($order, $newStatus);

        $order->save();
    }

    /**
     * Determine whether a status transition is allowed.
     */
    public function canTransition(
        OrderStatus $from,
        OrderStatus $to
    ): bool {
        return in_array(
            $to,
            $this->allowedTransitions($from),
            true
        );
    }

    /**
     * Define the valid order status transitions.
     *
     * @return array<OrderStatus>
     */
    private function allowedTransitions(OrderStatus $status): array
    {
        return match ($status) {
        OrderStatus::PENDING => [
        OrderStatus::CONFIRMED,
        OrderStatus::CANCELLED,
    ],

            OrderStatus::CONFIRMED => [
        OrderStatus::PROCESSING,
        OrderStatus::CANCELLED,
    ],

            OrderStatus::PROCESSING => [
        OrderStatus::PARTIALLY_SHIPPED,
        OrderStatus::SHIPPED,
        OrderStatus::CANCELLED,
    ],

            OrderStatus::PARTIALLY_SHIPPED => [
        OrderStatus::SHIPPED,
        OrderStatus::PARTIALLY_DELIVERED,
    ],

            OrderStatus::SHIPPED => [
        OrderStatus::PARTIALLY_DELIVERED,
        OrderStatus::DELIVERED,
    ],

            OrderStatus::PARTIALLY_DELIVERED => [
        OrderStatus::DELIVERED,
    ],

            OrderStatus::DELIVERED => [
        OrderStatus::COMPLETED,
    ],

            OrderStatus::CANCELLED,
            OrderStatus::COMPLETED => [],
        };
    }

    private function updateTimestamps(
        Order $order,
        OrderStatus $status
    ): void {
        match ($status) {
        OrderStatus::CONFIRMED =>
                $order->confirmed_at = now(),

            OrderStatus::CANCELLED =>
                $order->cancelled_at = now(),

            OrderStatus::COMPLETED =>
                $order->completed_at = now(),

            default => null,
        };
    }
}