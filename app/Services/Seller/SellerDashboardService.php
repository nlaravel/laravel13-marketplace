<?php

declare(strict_types=1);

namespace App\Services\Seller;

use App\Models\SellerOrder;
use App\Models\Store;
use BackedEnum;
use Illuminate\Database\Eloquent\Collection;

class SellerDashboardService
{
    public function storesCount(int $userId): int
    {
        return Store::query()
            ->whereHas('seller', function ($query) use ($userId): void {
                $query->where('user_id', $userId);
            })
            ->count();
    }

    public function ordersCount(int $userId): int
    {
        return SellerOrder::query()
            ->whereHas('store.seller', function ($query) use ($userId): void {
                $query->where('user_id', $userId);
            })
            ->count();
    }

    public function recentOrders(int $userId): Collection
    {
        return SellerOrder::query()
            ->whereHas('store.seller', function ($query) use ($userId): void {
                $query->where('user_id', $userId);
            })
            ->latest()
            ->limit(10)
            ->get();
    }

    public function ordersByStatus(int $userId): array
    {
        return SellerOrder::query()
            ->whereHas('store.seller', function ($query) use ($userId): void {
                $query->where('user_id', $userId);
            })
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->mapWithKeys(fn ($total, $status): array => [
                $status instanceof BackedEnum ? $status->value : $status => (int) $total,
            ])
            ->all();
    }
}
