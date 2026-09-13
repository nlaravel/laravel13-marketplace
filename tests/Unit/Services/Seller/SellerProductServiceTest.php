<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Seller;

use App\Enums\CategoryStatus;
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

    private SellerProductService $productService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productService = new SellerProductService;
    }

    public function test_it_creates_a_product_for_an_approved_store_and_active_category(): void
    {
        $user = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
            'status' => StoreStatus::APPROVED,
        ]);

        $category = Category::factory()->create([
            'status' => CategoryStatus::ACTIVE,
        ]);

        $product = $this->productService->createProduct($user->id, $store->id, [
            'category_id' => $category->id,
            'name' => 'Test Product',
            'description' => 'Test product description.',
        ]);

        $this->assertInstanceOf(Product::class, $product);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'store_id' => $store->id,
            'category_id' => $category->id,
            'name' => 'Test Product',
        ]);
    }

    public function test_it_cannot_create_a_product_for_a_pending_store(): void
    {
        $user = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
            'status' => StoreStatus::PENDING,
        ]);

        $category = Category::factory()->create([
            'status' => CategoryStatus::ACTIVE,
        ]);

        $this->expectException(ModelNotFoundException::class);

        try {
            $this->productService->createProduct($user->id, $store->id, [
                'category_id' => $category->id,
                'name' => 'Pending Store Product',
                'description' => 'This product must not be created.',
            ]);
        } finally {
            $this->assertDatabaseMissing('products', [
                'store_id' => $store->id,
                'name' => 'Pending Store Product',
            ]);
        }
    }

    public function test_it_cannot_create_a_product_for_an_inactive_category(): void
    {
        $user = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
            'status' => StoreStatus::APPROVED,
        ]);

        $category = Category::factory()->create([
            'status' => CategoryStatus::INACTIVE,
        ]);

        $this->expectException(ModelNotFoundException::class);

        try {
            $this->productService->createProduct($user->id, $store->id, [
                'category_id' => $category->id,
                'name' => 'Inactive Category Product',
                'description' => 'This product must not be created.',
            ]);
        } finally {
            $this->assertDatabaseMissing('products', [
                'store_id' => $store->id,
                'name' => 'Inactive Category Product',
            ]);
        }
    }

    public function test_it_generates_a_unique_slug_for_duplicate_product_names(): void
    {
        $user = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
            'status' => StoreStatus::APPROVED,
        ]);

        $category = Category::factory()->create([
            'status' => CategoryStatus::ACTIVE,
        ]);

        $firstProduct = $this->productService->createProduct($user->id, $store->id, [
            'category_id' => $category->id,
            'name' => 'Test Product',
        ]);

        $secondProduct = $this->productService->createProduct($user->id, $store->id, [
            'category_id' => $category->id,
            'name' => 'Test Product',
        ]);

        $this->assertSame('test-product', $firstProduct->slug);
        $this->assertSame('test-product-2', $secondProduct->slug);
    }
}
