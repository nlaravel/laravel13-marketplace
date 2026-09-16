<?php

declare(strict_types=1);

namespace Tests\Feature\Seller;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SellerProfile;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class SellerProductVariantControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate([
            'name' => 'seller',
            'guard_name' => 'web',
        ]);

        $this->app
            ->make(PermissionRegistrar::class)
            ->forgetCachedPermissions();
    }

    public function test_seller_can_get_variants_for_owned_product(): void
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $store->id,
        ]);

        ProductVariant::factory()->count(2)->create([
            'product_id' => $product->id,
        ]);

        $response = $this->actingAs($seller)
            ->getJson("/api/v1/seller/products/{$product->id}/variants");

        $response
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_seller_cannot_get_variants_for_product_owned_by_another_seller(): void
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        $otherSeller = User::factory()->create();
        $otherSeller->assignRole('seller');

        $otherSellerProfile = SellerProfile::factory()->create([
            'user_id' => $otherSeller->id,
        ]);

        $otherStore = Store::factory()->create([
            'seller_id' => $otherSellerProfile->id,
        ]);

        $otherProduct = Product::factory()->create([
            'store_id' => $otherStore->id,
        ]);

        ProductVariant::factory()->create([
            'product_id' => $otherProduct->id,
        ]);

        $response = $this->actingAs($seller)
            ->getJson("/api/v1/seller/products/{$otherProduct->id}/variants");

        $response->assertForbidden();
    }

    public function test_seller_can_get_owned_variant(): void
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $store->id,
        ]);

        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
        ]);

        $response = $this->actingAs($seller)
            ->getJson("/api/v1/seller/products/{$product->id}/variants/{$variant->id}");

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $variant->id)
            ->assertJsonPath('data.product_id', $product->id)
            ->assertJsonPath('data.sku', $variant->sku);
    }

    public function test_seller_cannot_get_variant_belonging_to_another_seller(): void
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        $otherSeller = User::factory()->create();
        $otherSeller->assignRole('seller');

        $otherSellerProfile = SellerProfile::factory()->create([
            'user_id' => $otherSeller->id,
        ]);

        $otherStore = Store::factory()->create([
            'seller_id' => $otherSellerProfile->id,
        ]);

        $otherProduct = Product::factory()->create([
            'store_id' => $otherStore->id,
        ]);

        $variant = ProductVariant::factory()->create([
            'product_id' => $otherProduct->id,
        ]);

        $response = $this->actingAs($seller)
            ->getJson("/api/v1/seller/products/{$otherProduct->id}/variants/{$variant->id}");

        $response->assertForbidden();
    }

    public function test_seller_can_create_variant_for_owned_product(): void
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $store->id,
        ]);

        $response = $this->actingAs($seller)
            ->postJson("/api/v1/seller/products/{$product->id}/variants", [
                'sku' => 'API-SKU-001',
                'price' => 99.99,
                'compare_at_price' => 119.99,
                'is_active' => true,
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.product_id', $product->id)
            ->assertJsonPath('data.sku', 'API-SKU-001')
            ->assertJsonPath('data.price', '99.99')
            ->assertJsonPath('data.compare_at_price', '119.99')
            ->assertJsonPath('data.is_active', true);

        $this->assertDatabaseHas('product_variants', [
            'product_id' => $product->id,
            'sku' => 'API-SKU-001',
        ]);
    }

    public function test_seller_cannot_create_variant_for_product_owned_by_another_seller(): void
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        $otherSeller = User::factory()->create();
        $otherSeller->assignRole('seller');

        $otherSellerProfile = SellerProfile::factory()->create([
            'user_id' => $otherSeller->id,
        ]);

        $otherStore = Store::factory()->create([
            'seller_id' => $otherSellerProfile->id,
        ]);

        $otherProduct = Product::factory()->create([
            'store_id' => $otherStore->id,
        ]);

        $response = $this->actingAs($seller)
            ->postJson("/api/v1/seller/products/{$otherProduct->id}/variants", [
                'sku' => 'FORBIDDEN-SKU-001',
                'price' => 50,
                'is_active' => true,
            ]);

        $response->assertForbidden();

        $this->assertDatabaseMissing('product_variants', [
            'sku' => 'FORBIDDEN-SKU-001',
        ]);
    }

    public function test_seller_cannot_create_variant_with_duplicate_sku(): void
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $store->id,
        ]);

        ProductVariant::factory()->create([
            'sku' => 'DUPLICATE-SKU-001',
        ]);

        $response = $this->actingAs($seller)
            ->postJson("/api/v1/seller/products/{$product->id}/variants", [
                'sku' => 'DUPLICATE-SKU-001',
                'price' => 75,
                'is_active' => true,
            ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['sku']);
    }

    public function test_seller_can_update_owned_variant(): void
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $store->id,
        ]);

        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'sku' => 'OLD-SKU-001',
            'price' => 50,
            'compare_at_price' => 60,
            'is_active' => true,
        ]);

        $response = $this->actingAs($seller)
            ->putJson("/api/v1/seller/products/{$product->id}/variants/{$variant->id}", [
                'sku' => 'NEW-SKU-001',
                'price' => 75.50,
                'compare_at_price' => 90,
                'is_active' => false,
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $variant->id)
            ->assertJsonPath('data.product_id', $product->id)
            ->assertJsonPath('data.sku', 'NEW-SKU-001')
            ->assertJsonPath('data.price', '75.50')
            ->assertJsonPath('data.compare_at_price', '90.00')
            ->assertJsonPath('data.is_active', false);

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'sku' => 'NEW-SKU-001',
            'price' => 75.50,
            'compare_at_price' => 90,
            'is_active' => false,
        ]);
    }

    public function test_seller_cannot_update_variant_owned_by_another_seller(): void
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        $otherSeller = User::factory()->create();
        $otherSeller->assignRole('seller');

        $otherSellerProfile = SellerProfile::factory()->create([
            'user_id' => $otherSeller->id,
        ]);

        $otherStore = Store::factory()->create([
            'seller_id' => $otherSellerProfile->id,
        ]);

        $otherProduct = Product::factory()->create([
            'store_id' => $otherStore->id,
        ]);

        $variant = ProductVariant::factory()->create([
            'product_id' => $otherProduct->id,
            'sku' => 'OTHER-SKU-001',
            'price' => 40,
        ]);

        $response = $this->actingAs($seller)
            ->putJson("/api/v1/seller/products/{$otherProduct->id}/variants/{$variant->id}", [
                'sku' => 'HACKED-SKU-001',
                'price' => 999,
                'is_active' => false,
            ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'sku' => 'OTHER-SKU-001',
            'price' => 40,
            'is_active' => true,
        ]);
    }

    public function test_seller_cannot_update_variant_with_duplicate_sku(): void
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $store->id,
        ]);

        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'sku' => 'CURRENT-SKU-001',
            'price' => 50,
        ]);

        ProductVariant::factory()->create([
            'sku' => 'EXISTING-SKU-001',
        ]);

        $response = $this->actingAs($seller)
            ->putJson("/api/v1/seller/products/{$product->id}/variants/{$variant->id}", [
                'sku' => 'EXISTING-SKU-001',
                'price' => 75,
                'is_active' => false,
            ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['sku']);

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'sku' => 'CURRENT-SKU-001',
            'price' => 50,
            'is_active' => true,
        ]);
    }

    public function test_seller_can_delete_owned_variant(): void
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $store->id,
        ]);

        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
        ]);

        $response = $this->actingAs($seller)
            ->deleteJson("/api/v1/seller/products/{$product->id}/variants/{$variant->id}");

        $response
            ->assertOk()
            ->assertJson([
                'message' => 'Product variant deleted successfully.',
            ]);

        $this->assertSoftDeleted('product_variants', [
            'id' => $variant->id,
        ]);
    }

    public function test_seller_cannot_delete_variant_owned_by_another_seller(): void
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        $otherSeller = User::factory()->create();
        $otherSeller->assignRole('seller');

        $otherSellerProfile = SellerProfile::factory()->create([
            'user_id' => $otherSeller->id,
        ]);

        $otherStore = Store::factory()->create([
            'seller_id' => $otherSellerProfile->id,
        ]);

        $otherProduct = Product::factory()->create([
            'store_id' => $otherStore->id,
        ]);

        $variant = ProductVariant::factory()->create([
            'product_id' => $otherProduct->id,
        ]);

        $response = $this->actingAs($seller)
            ->deleteJson("/api/v1/seller/products/{$otherProduct->id}/variants/{$variant->id}");

        $response->assertForbidden();

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'deleted_at' => null,
        ]);
    }

    public function test_guest_cannot_access_product_variants(): void
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $store->id,
        ]);

        $response = $this->getJson("/api/v1/seller/products/{$product->id}/variants");

        $response->assertUnauthorized();
    }

    public function test_customer_cannot_access_product_variants(): void
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $store->id,
        ]);

        $customer = User::factory()->create();

        $response = $this->actingAs($customer)
            ->getJson("/api/v1/seller/products/{$product->id}/variants");

        $response->assertForbidden();
    }

    public function test_sku_is_required_when_creating_variant(): void
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $store->id,
        ]);

        $response = $this->actingAs($seller)
            ->postJson("/api/v1/seller/products/{$product->id}/variants", [
                'price' => 50,
                'is_active' => true,
            ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['sku']);
    }

    public function test_sku_must_be_unique_when_creating_variant(): void
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $store->id,
        ]);

        ProductVariant::factory()->create([
            'sku' => 'EXISTING-SKU-001',
        ]);

        $response = $this->actingAs($seller)
            ->postJson("/api/v1/seller/products/{$product->id}/variants", [
                'sku' => 'EXISTING-SKU-001',
                'price' => 50,
                'is_active' => true,
            ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['sku']);
    }

    public function test_price_is_required_when_creating_variant(): void
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $store->id,
        ]);

        $response = $this->actingAs($seller)
            ->postJson("/api/v1/seller/products/{$product->id}/variants", [
                'sku' => 'VALID-SKU-001',
                'is_active' => true,
            ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['price']);
    }

    public function test_price_cannot_be_negative_when_creating_variant(): void
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $store->id,
        ]);

        $response = $this->actingAs($seller)
            ->postJson("/api/v1/seller/products/{$product->id}/variants", [
                'sku' => 'VALID-SKU-002',
                'price' => -10,
                'is_active' => true,
            ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['price']);
    }

    public function test_compare_at_price_must_be_greater_than_or_equal_to_price(): void
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $store->id,
        ]);

        $response = $this->actingAs($seller)
            ->postJson("/api/v1/seller/products/{$product->id}/variants", [
                'sku' => 'VALID-SKU-003',
                'price' => 100,
                'compare_at_price' => 90,
                'is_active' => true,
            ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['compare_at_price']);
    }

    public function test_seller_can_patch_owned_variant(): void
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $store->id,
        ]);

        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'sku' => 'PATCH-SKU-001',
            'price' => 50,
            'compare_at_price' => 60,
            'is_active' => true,
        ]);

        $response = $this->actingAs($seller)
            ->patchJson("/api/v1/seller/products/{$product->id}/variants/{$variant->id}", [
                'sku' => 'PATCH-SKU-002',
                'price' => 75,
                'compare_at_price' => 80,
                'is_active' => true,
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $variant->id)
            ->assertJsonPath('data.sku', 'PATCH-SKU-002')
            ->assertJsonPath('data.price', '75.00')
            ->assertJsonPath('data.compare_at_price', '80.00')
            ->assertJsonPath('data.is_active', true);

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'sku' => 'PATCH-SKU-002',
            'price' => 75,
            'compare_at_price' => 80,
            'is_active' => true,
        ]);
    }
}
