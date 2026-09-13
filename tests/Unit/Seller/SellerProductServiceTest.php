<?php

declare(strict_types=1);

namespace Tests\Unit\Seller;

use App\Enums\StoreStatus;
use App\Models\Category;
use App\Models\Product;
use App\Models\SellerProfile;
use App\Models\Store;
use App\Models\User;
use App\Services\Seller\SellerProductService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerProductServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_only_the_sellers_products(): void
    {
        $user = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $otherSeller = User::factory()->create();

        $otherSellerProfile = SellerProfile::factory()->create([
            'user_id' => $otherSeller->id,
        ]);

        $otherStore = Store::factory()->create([
            'seller_id' => $otherSellerProfile->id,
        ]);

        Product::factory()->count(2)->create([
            'store_id' => $store->id,
        ]);

        Product::factory()->create([
            'store_id' => $otherStore->id,
        ]);

        $service = app(SellerProductService::class);

        $products = $service->getProducts($user->id);

        $this->assertCount(2, $products);
        $this->assertTrue($products->every(fn (Product $product): bool => $product->store_id === $store->id));
    }

    public function test_it_returns_the_sellers_product(): void
    {
        $user = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $store->id,
        ]);

        $service = app(SellerProductService::class);

        $result = $service->getProduct($user->id, $product->id);

        $this->assertSame($product->id, $result->id);
    }

    public function test_seller_cannot_access_another_sellers_product(): void
    {
        $user = User::factory()->create();

        $otherSeller = User::factory()->create();

        $otherSellerProfile = SellerProfile::factory()->create([
            'user_id' => $otherSeller->id,
        ]);

        $otherStore = Store::factory()->create([
            'seller_id' => $otherSellerProfile->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $otherStore->id,
        ]);

        $service = app(SellerProductService::class);

        $this->expectException(ModelNotFoundException::class);

        $service->getProduct($user->id, $product->id);
    }

    public function test_it_creates_a_product_for_the_sellers_store(): void
    {
        $user = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
            'status' => StoreStatus::APPROVED,
        ]);

        $category = Category::factory()->create();

        $service = app(SellerProductService::class);

        $product = $service->createProduct($user->id, $store->id, [
            'category_id' => $category->id,
            'name' => 'Test Product',
            'description' => 'Test product description.',
        ]);

        $this->assertSame($store->id, $product->store_id);
        $this->assertSame($category->id, $product->category_id);
        $this->assertSame('Test Product', $product->name);
        $this->assertSame('test-product', $product->slug);
    }

    public function test_seller_cannot_create_a_product_in_another_sellers_store(): void
    {
        $user = User::factory()->create();

        $otherSeller = User::factory()->create();

        $otherSellerProfile = SellerProfile::factory()->create([
            'user_id' => $otherSeller->id,
        ]);

        $otherStore = Store::factory()->create([
            'seller_id' => $otherSellerProfile->id,
        ]);

        $category = Category::factory()->create();

        $service = app(SellerProductService::class);

        $this->expectException(ModelNotFoundException::class);

        $service->createProduct($user->id, $otherStore->id, [
            'category_id' => $category->id,
            'name' => 'Unauthorized Product',
            'description' => 'Should not be created.',
        ]);
    }

    public function test_it_generates_a_unique_slug_for_duplicate_product_names_in_the_same_store(): void
    {
        $user = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $category = Category::factory()->create();

        Product::factory()->create([
            'store_id' => $store->id,
            'category_id' => $category->id,
            'name' => 'Test Product',
            'slug' => 'test-product',
        ]);

        $service = app(SellerProductService::class);

        $product = $service->createProduct($user->id, $store->id, [
            'category_id' => $category->id,
            'name' => 'Test Product',
            'description' => 'Another product.',
        ]);

        $this->assertSame('test-product-2', $product->slug);
    }

    public function test_it_updates_the_sellers_product(): void
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
            'name' => 'Old Product Name',
            'slug' => 'old-product-name',
        ]);

        $newCategory = Category::factory()->create();

        $service = app(SellerProductService::class);

        $updatedProduct = $service->updateProduct($user->id, $product->id, [
            'category_id' => $newCategory->id,
            'name' => 'New Product Name',
            'description' => 'Updated description.',
        ]);

        $this->assertSame('New Product Name', $updatedProduct->name);
        $this->assertSame($newCategory->id, $updatedProduct->category_id);
        $this->assertSame('Updated description.', $updatedProduct->description);

        // Slug remains stable when the product name changes.
        $this->assertSame('old-product-name', $updatedProduct->slug);
    }

    public function test_seller_cannot_update_another_sellers_product(): void
    {
        $user = User::factory()->create();

        $otherSeller = User::factory()->create();

        $otherSellerProfile = SellerProfile::factory()->create([
            'user_id' => $otherSeller->id,
        ]);

        $otherStore = Store::factory()->create([
            'seller_id' => $otherSellerProfile->id,
        ]);

        $category = Category::factory()->create();

        $product = Product::factory()->create([
            'store_id' => $otherStore->id,
            'category_id' => $category->id,
        ]);

        $service = app(SellerProductService::class);

        $this->expectException(ModelNotFoundException::class);

        $service->updateProduct($user->id, $product->id, [
            'category_id' => $category->id,
            'name' => 'Unauthorized Update',
            'description' => 'Should not be updated.',
        ]);
    }

    public function test_it_deletes_the_sellers_product(): void
    {
        $user = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $store->id,
        ]);

        $service = app(SellerProductService::class);

        $service->deleteProduct($user->id, $product->id);

        $this->assertSoftDeleted('products', [
            'id' => $product->id,
        ]);
    }

    public function test_seller_cannot_delete_another_sellers_product(): void
    {
        $user = User::factory()->create();

        $otherSeller = User::factory()->create();

        $otherSellerProfile = SellerProfile::factory()->create([
            'user_id' => $otherSeller->id,
        ]);

        $otherStore = Store::factory()->create([
            'seller_id' => $otherSellerProfile->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $otherStore->id,
        ]);

        $service = app(SellerProductService::class);

        $this->expectException(ModelNotFoundException::class);

        try {
            $service->deleteProduct($user->id, $product->id);
        } finally {
            $this->assertDatabaseHas('products', [
                'id' => $product->id,
                'deleted_at' => null,
            ]);
        }
    }
}
