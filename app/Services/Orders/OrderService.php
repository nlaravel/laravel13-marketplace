<?php

declare(strict_types=1);

namespace App\Services\Orders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderService
{
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
}
