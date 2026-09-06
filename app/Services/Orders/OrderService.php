<?php

declare(strict_types=1);

namespace App\Services\Orders;

use App\Enums\OrderStatus;
use App\Exceptions\OrderException;
use App\Models\Order;
use App\Models\User;
use App\Services\Inventory\InventoryReservationService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(private readonly InventoryReservationService $inventoryReservationService, ) {}

    public function getCustomerOrders(User $customer): LengthAwarePaginator
    {
        return Order::query()
            ->where('customer_id', $customer->id)
            ->with([
                'items',
                'sellerOrders.items',
                'addresses',
            ])
            ->latest()
            ->paginate(10);
    }

    public function getCustomerOrder(User $customer, int $orderId): Order
    {
        return Order::query()
            ->where('customer_id', $customer->id)
            ->with([
                'items',
                'sellerOrders.items',
                'addresses',
            ])
            ->findOrFail($orderId);
    }


    public function cancelCustomerOrder(User $customer, int $orderId): Order
    {
        return DB::transaction(function () use ($customer, $orderId): Order {
            $order = $this->getCustomerOrder($customer, $orderId);

            if (! in_array($order->status, [
                OrderStatus::PENDING,
                OrderStatus::CONFIRMED,
            ], true)) {
                throw new OrderException('Order cannot be cancelled.');
            }

            $this->inventoryReservationService->release($order);

            $order->status = OrderStatus::CANCELLED;
            $order->cancelled_at = now();
            $order->save();

            return $order;
        });
    }
}
