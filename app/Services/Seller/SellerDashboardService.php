<?php

declare(strict_types=1);

namespace App\Services\Seller;

use App\Models\SellerOrder;
use App\Models\Store;
use BackedEnum;
use Illuminate\Database\Eloquent\Collection;

class SellerDashboardService
{
    public function storesCount(int $sellerId): int
    {
        return Store::query()
            ->whereHas('seller', function ($query) use ($sellerId): void {
                $query->where('user_id', $sellerId);
            })
            ->count();
    }

    public function ordersCount(int $sellerId): int
    {
        return SellerOrder::query()
            ->whereHas('store.seller', function ($query) use ($sellerId): void {
                $query->where('user_id', $sellerId);
            })
            ->count();
    }

    public function recentOrders(int $sellerId): Collection
    {
        return SellerOrder::query()
            ->whereHas('store.seller', function ($query) use ($sellerId): void {
                $query->where('user_id', $sellerId);
            })
            ->latest()
            ->limit(5)
            ->get();
    }

    public function ordersByStatus(int $sellerId): array
    {
        return SellerOrder::query()
            ->whereHas('store.seller', function ($query) use ($sellerId): void {
                $query->where('user_id', $sellerId);
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
