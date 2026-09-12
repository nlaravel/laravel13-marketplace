<?php

declare(strict_types=1);

namespace App\Services\Seller;

use App\Enums\StoreStatus;
use App\Models\SellerProfile;
use App\Models\Store;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class SellerStoreService
{
    public function getStores(int $sellerId): Collection
    {
        return Store::query()
            ->whereHas('seller', function ($query) use ($sellerId): void {
                $query->where('user_id', $sellerId);
            })
            ->latest()
            ->get();
    }

    public function getStore(int $sellerId, int $storeId): Store
    {
        return Store::query()
            ->whereKey($storeId)
            ->whereHas('seller', function ($query) use ($sellerId): void {
                $query->where('user_id', $sellerId);
            })
            ->firstOrFail();
    }

    public function createStore(int $sellerId, array $data): Store
    {
        $sellerProfile = SellerProfile::query()
            ->where('user_id', $sellerId)
            ->firstOrFail();

        $slug = $this->generateUniqueSlug($data['name']);

        return Store::query()->create([
            'seller_id' => $sellerProfile->id,
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'status' => StoreStatus::PENDING,
        ]);
    }

    private function generateUniqueSlug(string $name): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 2;

        while (Store::query()->where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    public function updateStore(int $sellerId, int $storeId, array $data): Store
    {
        $store = $this->getStore($sellerId, $storeId);

        $store->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        return $store->refresh();
    }
}
