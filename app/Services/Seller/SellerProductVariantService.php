<?php

declare(strict_types=1);

namespace App\Services\Seller;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SellerProductVariantService
{
    public function getVariants(int $userId, int $productId): Collection
    {
        $this->getProduct($userId, $productId);

        return ProductVariant::query()
            ->where('product_id', $productId)
            ->latest()
            ->get();
    }

    public function getVariant(int $userId, int $variantId): ProductVariant
    {
        return ProductVariant::query()
            ->whereKey($variantId)
            ->whereHas('product.store.seller', function ($query) use ($userId): void {
                $query->where('user_id', $userId);
            })
            ->firstOrFail();
    }

    public function createVariant(int $userId, int $productId, array $data): ProductVariant
    {
        $product = $this->getProduct($userId, $productId);

        return ProductVariant::query()->create([
            'product_id' => $product->id,
            'sku' => $data['sku'],
            'price' => $data['price'],
            'compare_at_price' => $data['compare_at_price'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    public function updateVariant(int $userId, int $variantId, array $data): ProductVariant
    {
        $variant = $this->getVariant($userId, $variantId);

        $variant->update([
            'sku' => $data['sku'],
            'price' => $data['price'],
            'compare_at_price' => $data['compare_at_price'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);

        return $variant->refresh();
    }

    public function deleteVariant(int $userId, int $variantId): void
    {
        $variant = $this->getVariant($userId, $variantId);

        $variant->delete();
    }

    private function getProduct(int $userId, int $productId): Product
    {
        $product = Product::query()
            ->whereKey($productId)
            ->whereHas('store.seller', function ($query) use ($userId): void {
                $query->where('user_id', $userId);
            })
            ->first();

        if ($product === null) {
            throw (new ModelNotFoundException)
                ->setModel(Product::class, [$productId]);
        }

        return $product;
    }
}
