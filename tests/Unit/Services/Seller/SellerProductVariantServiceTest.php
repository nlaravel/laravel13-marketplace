<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Seller;

use App\Exceptions\SellerException;
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

    private SellerProductVariantService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(SellerProductVariantService::class);
    }

    public function test_it_gets_variants_for_a_product_owned_by_the_seller(): void
    {
        $user = User::factory()->create();

        $sellerProfile = SellerProfile::factory()
            ->for($user)
            ->create();

        $store = Store::factory()
            ->for($sellerProfile, 'seller')
            ->create();

        $product = Product::factory()
            ->for($store)
            ->create();

        ProductVariant::factory()
            ->for($product)
            ->create([
                'sku' => 'SKU-001',
            ]);

        ProductVariant::factory()
            ->for($product)
            ->create([
                'sku' => 'SKU-002',
            ]);

        $variants = $this->service->getVariants($user->id, $product->id);

        $this->assertCount(2, $variants);

        $this->assertTrue($variants->every(fn (ProductVariant $variant): bool => $variant->product_id === $product->id));
    }

    public function test_it_cannot_get_variants_for_a_product_owned_by_another_seller(): void
    {
        $owner = User::factory()->create();

        $sellerProfile = SellerProfile::factory()
            ->for($owner)
            ->create();

        $store = Store::factory()
            ->for($sellerProfile, 'seller')
            ->create();

        $product = Product::factory()
            ->for($store)
            ->create();

        ProductVariant::factory()
            ->for($product)
            ->create([
                'sku' => 'SKU-001',
            ]);

        $otherUser = User::factory()->create();

        $this->expectException(ModelNotFoundException::class);

        $this->service->getVariants($otherUser->id, $product->id);
    }

    public function test_it_creates_a_variant_for_a_product_owned_by_the_seller(): void
    {
        $user = User::factory()->create();

        $sellerProfile = SellerProfile::factory()
            ->for($user)
            ->create();

        $store = Store::factory()
            ->for($sellerProfile, 'seller')
            ->create();

        $product = Product::factory()
            ->for($store)
            ->create();

        $variant = $this->service->createVariant($user->id, $product->id, [
            'sku' => 'SKU-001',
            'price' => 99.99,
            'compare_at_price' => 119.99,
            'is_active' => true,
        ]);

        $this->assertInstanceOf(ProductVariant::class, $variant);

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'product_id' => $product->id,
            'sku' => 'SKU-001',
            'price' => '99.99',
            'compare_at_price' => '119.99',
            'is_active' => true,
        ]);
    }

    public function test_it_updates_a_variant_owned_by_the_seller(): void
    {
        $user = User::factory()->create();

        $sellerProfile = SellerProfile::factory()
            ->for($user)
            ->create();

        $store = Store::factory()
            ->for($sellerProfile, 'seller')
            ->create();

        $product = Product::factory()
            ->for($store)
            ->create();

        $variant = ProductVariant::factory()
            ->for($product)
            ->create([
                'sku' => 'SKU-001',
                'price' => 50,
                'compare_at_price' => 60,
                'is_active' => true,
            ]);

        $updatedVariant = $this->service->updateVariant($user->id, $variant->id, [
            'sku' => 'SKU-001',
            'price' => 75,
            'compare_at_price' => 90,
            'is_active' => false,
        ]);

        $this->assertSame($variant->id, $updatedVariant->id);

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'sku' => 'SKU-001',
            'price' => '75.00',
            'compare_at_price' => '90.00',
            'is_active' => false,
        ]);
    }

    public function test_it_deletes_a_variant_owned_by_the_seller(): void
    {
        $user = User::factory()->create();

        $sellerProfile = SellerProfile::factory()
            ->for($user)
            ->create();

        $store = Store::factory()
            ->for($sellerProfile, 'seller')
            ->create();

        $product = Product::factory()
            ->for($store)
            ->create();

        $variant = ProductVariant::factory()
            ->for($product)
            ->create([
                'sku' => 'SKU-001',
            ]);

        $this->service->deleteVariant($user->id, $variant->id);

        $this->assertSoftDeleted('product_variants', [
            'id' => $variant->id,
        ]);
    }

    public function test_it_rejects_duplicate_sku_when_creating_a_variant(): void
    {
        $user = User::factory()->create();

        $sellerProfile = SellerProfile::factory()
            ->for($user)
            ->create();

        $store = Store::factory()
            ->for($sellerProfile, 'seller')
            ->create();

        $product = Product::factory()
            ->for($store)
            ->create();

        ProductVariant::factory()
            ->for($product)
            ->create([
                'sku' => 'SKU-001',
            ]);

        $this->expectException(SellerException::class);

        $this->expectExceptionMessage('This SKU is already in use. Please choose a different SKU.');

        $this->service->createVariant($user->id, $product->id, [
            'sku' => 'SKU-001',
            'price' => 99.99,
            'compare_at_price' => null,
            'is_active' => true,
        ]);
    }

    public function test_it_rejects_duplicate_sku_when_updating_a_variant(): void
    {
        $user = User::factory()->create();

        $sellerProfile = SellerProfile::factory()
            ->for($user)
            ->create();

        $store = Store::factory()
            ->for($sellerProfile, 'seller')
            ->create();

        $product = Product::factory()
            ->for($store)
            ->create();

        $firstVariant = ProductVariant::factory()
            ->for($product)
            ->create([
                'sku' => 'SKU-001',
            ]);

        $secondVariant = ProductVariant::factory()
            ->for($product)
            ->create([
                'sku' => 'SKU-002',
                'price' => 50,
            ]);

        $this->expectException(SellerException::class);

        $this->expectExceptionMessage('This SKU is already in use. Please choose a different SKU.');

        try {
            $this->service->updateVariant($user->id, $secondVariant->id, [
                'sku' => $firstVariant->sku,
                'price' => 75,
                'compare_at_price' => null,
                'is_active' => true,
            ]);
        } finally {
            $this->assertDatabaseHas('product_variants', [
                'id' => $secondVariant->id,
                'sku' => 'SKU-002',
                'price' => '50.00',
            ]);
        }
    }

    public function test_it_allows_a_variant_to_keep_its_existing_sku_when_updating(): void
    {
        $user = User::factory()->create();

        $sellerProfile = SellerProfile::factory()
            ->for($user)
            ->create();

        $store = Store::factory()
            ->for($sellerProfile, 'seller')
            ->create();

        $product = Product::factory()
            ->for($store)
            ->create();

        $variant = ProductVariant::factory()
            ->for($product)
            ->create([
                'sku' => 'SKU-001',
                'price' => 50,
            ]);

        $updatedVariant = $this->service->updateVariant($user->id, $variant->id, [
            'sku' => 'SKU-001',
            'price' => 75,
            'compare_at_price' => null,
            'is_active' => true,
        ]);

        $this->assertSame($variant->id, $updatedVariant->id);

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'sku' => 'SKU-001',
            'price' => '75.00',
        ]);
    }
}
