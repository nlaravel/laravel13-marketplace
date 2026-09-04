<?php

declare(strict_types=1);

namespace App\Services\Customer;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

class CustomerDashboardService
{
    public function ordersCount(int $customerId): int
    {
        return Order::query()
            ->where('customer_id', $customerId)
            ->count();
    }

    public function addressesCount(int $customerId): int
    {
        return Address::query()
            ->where('user_id', $customerId)
            ->count();
    }

    public function cartItemsCount(int $customerId): int
    {
        return Cart::query()
            ->where('user_id', $customerId)
            ->with('items')
            ->first()?->items
            ->sum('quantity') ?? 0;
    }

    public function recentOrders(int $customerId): Collection
    {
        return Order::query()
            ->where('customer_id', $customerId)
            ->latest()
            ->limit(5)
            ->get();
    }

    public function defaultAddress(int $customerId): ?Address
    {
        return Address::query()
            ->where('user_id', $customerId)
            ->where('is_default', true)
            ->first();
    }
}
