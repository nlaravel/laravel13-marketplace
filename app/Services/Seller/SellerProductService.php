<?php

declare(strict_types=1);

namespace App\Services\Seller;

use App\Models\Product;
use App\Models\Store;
use App\Services\Concerns\HandlesUniqueConstraintRetries;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SellerProductService
{
    use HandlesUniqueConstraintRetries;

    public function getProducts(int $userId): Collection
    {
        return Product::query()
            ->whereHas('store.seller', function ($query) use ($userId): void {
                $query->where('user_id', $userId);
            })
            ->latest()
            ->get();
    }

    public function getProduct(int $userId, int $productId): Product
    {
        return Product::query()
            ->whereKey($productId)
            ->whereHas('store.seller', function ($query) use ($userId): void {
                $query->where('user_id', $userId);
            })
            ->firstOrFail();
    }

    public function createProduct(int $userId, int $storeId, array $data): Product
    {
        $store = Store::query()
            ->whereKey($storeId)
            ->whereHas('seller', function ($query) use ($userId): void {
                $query->where('user_id', $userId);
            })
            ->first();

        if ($store === null) {
            throw (new ModelNotFoundException)->setModel(Store::class, [$storeId]);
        }

        try {
            return $this->attemptWithRetry(function () use ($store, $data): Product {
                return DB::transaction(function () use ($store, $data): Product {
                    $slug = $this->generateUniqueSlug($store->id, $data['name']);

                    return Product::query()->create([
                        'store_id' => $store->id,
                        'category_id' => $data['category_id'],
                        'name' => $data['name'],
                        'slug' => $slug,
                        'description' => $data['description'] ?? null,
                    ]);
                });
            });
        } catch (QueryException $exception) {
            if ($this->isUniqueConstraintViolation($exception)) {
                throw new SellerException('Unable to create the product right now. Please try again.');
            }

            throw $exception;
        }
    }

    public function updateProduct(int $userId, int $productId, array $data): Product
    {
        $product = $this->getProduct($userId, $productId);

        $product->update([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        return $product->refresh();
    }

    public function deleteProduct(int $userId, int $productId): void
    {
        $product = $this->getProduct($userId, $productId);

        $product->delete();
    }

    private function generateUniqueSlug(int $storeId, string $name): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 2;

        while (
            Product::query()
                ->where('store_id', $storeId)
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
