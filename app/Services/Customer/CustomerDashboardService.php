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

    public function ordersByMonth(int $customerId): array
    {
        return Order::query()
            ->where('customer_id', $customerId)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(fn($order): array => [
                $order->month => (int) $order->total,
            ])
        ->toArray();
    }

    public function defaultAddress(int $customerId): ?Address
    {
        return Address::query()
            ->where('user_id', $customerId)
            ->where('is_default', true)
            ->first();
    }

    public function ordersByStatus(int $customerId): array
    {
        return Order::query()
            ->where('customer_id', $customerId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->mapWithKeys(fn($total, $status): array => [
                $status instanceof \BackedEnum ? $status->value : $status => (int) $total,
            ])
        ->all();
    }
}
