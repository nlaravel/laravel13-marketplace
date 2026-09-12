<?php

declare(strict_types=1);

namespace App\Services\Seller;

use App\Enums\StoreStatus;
use App\Exceptions\SellerException;
use App\Models\SellerProfile;
use App\Models\Store;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SellerStoreService
{
    public function getStores(int $userId): Collection
    {
        return Store::query()
            ->whereHas('seller', function ($query) use ($userId): void {
                $query->where('user_id', $userId);
            })
            ->latest()
            ->get();
    }

    public function getStore(int $userId, int $storeId): Store
    {
        return Store::query()
            ->whereKey($storeId)
            ->whereHas('seller', function ($query) use ($userId): void {
                $query->where('user_id', $userId);
            })
            ->firstOrFail();
    }

    public function createStore(int $userId, array $data): Store
    {
        $sellerProfile = SellerProfile::query()
            ->where('user_id', $userId)
            ->first();

        if ($sellerProfile === null) {
            throw new SellerException('Please complete your seller profile before creating a store.');
        }

        $attempts = 0;

        while ($attempts < 3) {
            $attempts++;

            try {
                return DB::transaction(function () use ($sellerProfile, $data): Store {
                    $slug = $this->generateUniqueSlug($data['name']);

                    return Store::query()->create([
                        'seller_id' => $sellerProfile->id,
                        'name' => $data['name'],
                        'slug' => $slug,
                        'description' => $data['description'] ?? null,
                        'status' => StoreStatus::PENDING,
                    ]);
                });
            } catch (QueryException $exception) {
                if (! $this->isUniqueConstraintViolation($exception)) {
                    throw $exception;
                }
            }
        }

        throw new SellerException('Unable to create the store right now. Please try again.');
    }

    public function updateStore(int $userId, int $storeId, array $data): Store
    {
        $store = $this->getStore($userId, $storeId);

        $store->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        return $store->refresh();
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

    private function isUniqueConstraintViolation(QueryException $exception): bool
    {
        return $exception->errorInfo[1] ?? null === 1062;
    }
}
