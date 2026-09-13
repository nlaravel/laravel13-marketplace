<?php

declare(strict_types=1);

namespace Tests\Unit\Seller;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SellerProfile;
use App\Models\Store;
use App\Models\User;
use App\Services\Seller\SellerProductVariantService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerProductVariantServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_only_variants_belonging_to_the_sellers_product(): void
    {
        $user = User::factory()->create();
        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $user->id,
        ]);
        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);
        $category = Category::factory()->create();

        $product = Product::factory()->create([
            'store_id' => $store->id,
            'category_id' => $category->id,
        ]);

        $otherUser = User::factory()->create();
        $otherSellerProfile = SellerProfile::factory()->create([
            'user_id' => $otherUser->id,
        ]);
        $otherStore = Store::factory()->create([
            'seller_id' => $otherSellerProfile->id,
        ]);
        $otherProduct = Product::factory()->create([
            'store_id' => $otherStore->id,
            'category_id' => $category->id,
        ]);

        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
        ]);

        ProductVariant::factory()->create([
            'product_id' => $otherProduct->id,
        ]);

        $service = app(SellerProductVariantService::class);

        $variants = $service->getVariants($user->id, $product->id);

        $this->assertCount(1, $variants);
        $this->assertTrue($variants->contains($variant));
    }

    public function test_it_returns_the_sellers_variant(): void
    {
        [$user, $product] = $this->createSellerProduct();

        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
        ]);

        $service = app(SellerProductVariantService::class);

        $result = $service->getVariant($user->id, $variant->id);

        $this->assertTrue($result->is($variant));
    }

    public function test_seller_cannot_access_another_sellers_variant(): void
    {
        [$user, $product] = $this->createSellerProduct();

        [$otherUser, $otherProduct] = $this->createSellerProduct();

        $variant = ProductVariant::factory()->create([
            'product_id' => $otherProduct->id,
        ]);

        $service = app(SellerProductVariantService::class);

        $this->expectException(ModelNotFoundException::class);

        $service->getVariant($user->id, $variant->id);
    }

    public function test_it_creates_a_variant_for_the_sellers_product(): void
    {
        [$user, $product] = $this->createSellerProduct();

        $service = app(SellerProductVariantService::class);

        $variant = $service->createVariant($user->id, $product->id, [
            'sku' => 'SKU-TEST-001',
            'price' => 99.99,
            'compare_at_price' => 119.99,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'product_id' => $product->id,
            'sku' => 'SKU-TEST-001',
            'price' => 99.99,
            'compare_at_price' => 119.99,
            'is_active' => true,
        ]);
    }

    public function test_seller_cannot_create_variant_for_another_sellers_product(): void
    {
        [$user, $product] = $this->createSellerProduct();

        [$otherUser, $otherProduct] = $this->createSellerProduct();

        $service = app(SellerProductVariantService::class);

        $this->expectException(ModelNotFoundException::class);

        $service->createVariant($user->id, $otherProduct->id, [
            'sku' => 'SKU-TEST-002',
            'price' => 49.99,
        ]);
    }

    public function test_it_updates_the_sellers_variant(): void
    {
        [$user, $product] = $this->createSellerProduct();

        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'sku' => 'SKU-OLD-001',
            'price' => 50.00,
            'compare_at_price' => 60.00,
            'is_active' => true,
        ]);

        $service = app(SellerProductVariantService::class);

        $updatedVariant = $service->updateVariant($user->id, $variant->id, [
            'sku' => 'SKU-NEW-001',
            'price' => 75.00,
            'compare_at_price' => 90.00,
            'is_active' => false,
        ]);

        $this->assertSame('SKU-NEW-001', $updatedVariant->sku);
        $this->assertSame('75.00', $updatedVariant->price);
        $this->assertSame('90.00', $updatedVariant->compare_at_price);
        $this->assertFalse($updatedVariant->is_active);
    }

    public function test_seller_cannot_update_another_sellers_variant(): void
    {
        [$user, $product] = $this->createSellerProduct();

        [$otherUser, $otherProduct] = $this->createSellerProduct();

        $variant = ProductVariant::factory()->create([
            'product_id' => $otherProduct->id,
            'sku' => 'SKU-OTHER-001',
        ]);

        $service = app(SellerProductVariantService::class);

        $this->expectException(ModelNotFoundException::class);

        $service->updateVariant($user->id, $variant->id, [
            'sku' => 'SKU-HACK-001',
            'price' => 1.00,
        ]);
    }

    public function test_it_deletes_the_sellers_variant(): void
    {
        [$user, $product] = $this->createSellerProduct();

        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
        ]);

        $service = app(SellerProductVariantService::class);

        $service->deleteVariant($user->id, $variant->id);

        $this->assertSoftDeleted('product_variants', [
            'id' => $variant->id,
        ]);
    }

    public function test_seller_cannot_delete_another_sellers_variant(): void
    {
        [$user, $product] = $this->createSellerProduct();

        [$otherUser, $otherProduct] = $this->createSellerProduct();

        $variant = ProductVariant::factory()->create([
            'product_id' => $otherProduct->id,
        ]);

        $service = app(SellerProductVariantService::class);

        $this->expectException(ModelNotFoundException::class);

        try {
            $service->deleteVariant($user->id, $variant->id);
        } finally {
            $this->assertDatabaseHas('product_variants', [
                'id' => $variant->id,
                'deleted_at' => null,
            ]);
        }
    }

    private function createSellerProduct(): array
    {
        $user = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $category = Category::factory()->create();

        $product = Product::factory()->create([
            'store_id' => $store->id,
            'category_id' => $category->id,
        ]);

        return [$user, $product];
    }
}
