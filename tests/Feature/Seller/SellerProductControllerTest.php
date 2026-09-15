<?php

declare(strict_types=1);

namespace Tests\Feature\Seller;

use App\Enums\StoreStatus;
use App\Models\Category;
use App\Models\Product;
use App\Models\SellerProfile;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SellerProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_can_get_their_products(): void
    {
        $seller = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        Product::factory()->create([
            'store_id' => $store->id,
        ]);

        Product::factory()->create([
            'store_id' => $store->id,
        ]);

        $otherSeller = User::factory()->create();

        $otherSellerProfile = SellerProfile::factory()->create([
            'user_id' => $otherSeller->id,
        ]);

        $otherStore = Store::factory()->create([
            'seller_id' => $otherSellerProfile->id,
        ]);

        Product::factory()->create([
            'store_id' => $otherStore->id,
        ]);

        $this->assignSellerRole($seller);

        $response = $this->actingAs($seller, 'sanctum')
            ->getJson('/api/v1/seller/products');

        $response
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_seller_can_get_their_product(): void
    {
        $seller = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $store->id,
        ]);

        $this->assignSellerRole($seller);

        $response = $this->actingAs($seller, 'sanctum')
            ->getJson("/api/v1/seller/products/{$product->id}");

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $product->id)
            ->assertJsonPath('data.store_id', $product->store_id)
            ->assertJsonPath('data.category_id', $product->category_id)
            ->assertJsonPath('data.name', $product->name)
            ->assertJsonPath('data.slug', $product->slug)
            ->assertJsonPath('data.status', $product->status->value);
    }

    public function test_seller_cannot_see_another_sellers_product(): void
    {
        $seller = User::factory()->create();

        SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

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

        $this->assignSellerRole($seller);

        $response = $this->actingAs($seller, 'sanctum')
            ->getJson("/api/v1/seller/products/{$product->id}");

        $response->assertForbidden();
    }

    public function test_customer_cannot_access_seller_products_api(): void
    {
        $customer = User::factory()->create();

        $response = $this->actingAs($customer, 'sanctum')
            ->getJson('/api/v1/seller/products');

        $response->assertForbidden();
    }

    public function test_guest_cannot_access_seller_products_api(): void
    {
        $response = $this->getJson('/api/v1/seller/products');

        $response->assertUnauthorized();
    }

    public function test_seller_can_create_a_product_via_api(): void
    {
        $seller = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
            'status' => StoreStatus::APPROVED,
        ]);

        $category = Category::factory()->create();

        $this->assignSellerRole($seller);

        $response = $this->actingAs($seller, 'sanctum')
            ->postJson('/api/v1/seller/products', [
                'store_id' => $store->id,
                'category_id' => $category->id,
                'name' => 'API Test Product',
                'description' => 'Product created through API',
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.store_id', $store->id)
            ->assertJsonPath('data.category_id', $category->id)
            ->assertJsonPath('data.name', 'API Test Product')
            ->assertJsonPath('data.slug', 'api-test-product')
            ->assertJsonPath('data.description', 'Product created through API')
            ->assertJsonPath('data.status', 'draft');

        $this->assertDatabaseHas('products', [
            'store_id' => $store->id,
            'category_id' => $category->id,
            'name' => 'API Test Product',
            'slug' => 'api-test-product',
            'description' => 'Product created through API',
            'status' => 'draft',
        ]);
    }

    public function test_customer_cannot_create_a_product_via_api(): void
    {
        $customer = User::factory()->create();

        $store = Store::factory()->create([
            'status' => StoreStatus::APPROVED,
        ]);

        $category = Category::factory()->create();

        $response = $this->actingAs($customer, 'sanctum')
            ->postJson('/api/v1/seller/products', [
                'store_id' => $store->id,
                'category_id' => $category->id,
                'name' => 'Customer Product',
            ]);

        $response->assertForbidden();

        $this->assertDatabaseCount('products', 0);
    }

    public function test_guest_cannot_create_a_product_via_api(): void
    {
        $store = Store::factory()->create([
            'status' => StoreStatus::APPROVED,
        ]);

        $category = Category::factory()->create();

        $response = $this->postJson('/api/v1/seller/products', [
            'store_id' => $store->id,
            'category_id' => $category->id,
            'name' => 'Guest Product',
        ]);

        $response->assertUnauthorized();

        $this->assertDatabaseCount('products', 0);
    }

    public function test_seller_cannot_create_a_product_without_an_approved_store(): void
    {
        $seller = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
            'status' => StoreStatus::PENDING,
        ]);

        $category = Category::factory()->create();

        $this->assignSellerRole($seller);

        $response = $this->actingAs($seller, 'sanctum')
            ->postJson('/api/v1/seller/products', [
                'store_id' => $store->id,
                'category_id' => $category->id,
                'name' => 'Pending Store Product',
            ]);

        $response->assertForbidden();

        $this->assertDatabaseCount('products', 0);
    }

    public function test_seller_cannot_create_a_product_in_another_sellers_store(): void
    {
        $seller = User::factory()->create();

        SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $otherSeller = User::factory()->create();

        $otherSellerProfile = SellerProfile::factory()->create([
            'user_id' => $otherSeller->id,
        ]);

        $otherStore = Store::factory()->create([
            'seller_id' => $otherSellerProfile->id,
            'status' => StoreStatus::APPROVED,
        ]);

        $category = Category::factory()->create();

        $this->assignSellerRole($seller);

        $response = $this->actingAs($seller, 'sanctum')
            ->postJson('/api/v1/seller/products', [
                'store_id' => $otherStore->id,
                'category_id' => $category->id,
                'name' => 'Unauthorized Product',
            ]);

        $response->assertForbidden();

        $this->assertDatabaseCount('products', 0);
    }

    public function test_create_product_api_requires_a_valid_name(): void
    {
        $seller = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
            'status' => StoreStatus::APPROVED,
        ]);

        $category = Category::factory()->create();

        $this->assignSellerRole($seller);

        $response = $this->actingAs($seller, 'sanctum')
            ->postJson('/api/v1/seller/products', [
                'store_id' => $store->id,
                'category_id' => $category->id,
                'name' => 'ab',
            ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('name');

        $this->assertDatabaseCount('products', 0);
    }

    public function test_create_product_api_requires_store_id(): void
    {
        $seller = User::factory()->create();

        SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        Category::factory()->create();

        $this->assignSellerRole($seller);

        $response = $this->actingAs($seller, 'sanctum')
            ->postJson('/api/v1/seller/products', [
                'category_id' => Category::query()->first()->id,
                'name' => 'Missing Store Product',
            ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('store_id');

        $this->assertDatabaseCount('products', 0);
    }

    public function test_create_product_api_requires_category_id(): void
    {
        $seller = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
            'status' => StoreStatus::APPROVED,
        ]);

        $this->assignSellerRole($seller);

        $response = $this->actingAs($seller, 'sanctum')
            ->postJson('/api/v1/seller/products', [
                'store_id' => $store->id,
                'name' => 'Missing Category Product',
            ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('category_id');

        $this->assertDatabaseCount('products', 0);
    }

    public function test_seller_can_update_their_product_via_api(): void
    {
        $seller = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $oldCategory = Category::factory()->create();
        $newCategory = Category::factory()->create();

        $product = Product::factory()->create([
            'store_id' => $store->id,
            'category_id' => $oldCategory->id,
            'name' => 'Old Product Name',
            'description' => 'Old description',
        ]);

        $this->assignSellerRole($seller);

        $response = $this->actingAs($seller, 'sanctum')
            ->putJson("/api/v1/seller/products/{$product->id}", [
                'category_id' => $newCategory->id,
                'name' => 'Updated Product Name',
                'description' => 'Updated description',
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $product->id)
            ->assertJsonPath('data.store_id', $store->id)
            ->assertJsonPath('data.category_id', $newCategory->id)
            ->assertJsonPath('data.name', 'Updated Product Name')
            ->assertJsonPath('data.slug', $product->slug)
            ->assertJsonPath('data.description', 'Updated description')
            ->assertJsonPath('data.status', $product->status->value);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'store_id' => $store->id,
            'category_id' => $newCategory->id,
            'name' => 'Updated Product Name',
            'slug' => $product->slug,
            'description' => 'Updated description',
            'status' => $product->status->value,
        ]);
    }

    public function test_seller_cannot_update_another_sellers_product_via_api(): void
    {
        $seller = User::factory()->create();

        SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $otherSeller = User::factory()->create();

        $otherSellerProfile = SellerProfile::factory()->create([
            'user_id' => $otherSeller->id,
        ]);

        $otherStore = Store::factory()->create([
            'seller_id' => $otherSellerProfile->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $otherStore->id,
            'name' => 'Original Product',
            'description' => 'Original description',
        ]);

        $this->assignSellerRole($seller);

        $response = $this->actingAs($seller, 'sanctum')
            ->putJson("/api/v1/seller/products/{$product->id}", [
                'category_id' => $product->category_id,
                'name' => 'Hacked Product',
                'description' => 'Should not be updated',
            ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'store_id' => $otherStore->id,
            'name' => 'Original Product',
            'description' => 'Original description',
        ]);
    }

    public function test_customer_cannot_update_a_product_via_api(): void
    {
        $customer = User::factory()->create();

        $seller = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $store->id,
        ]);

        $response = $this->actingAs($customer, 'sanctum')
            ->putJson("/api/v1/seller/products/{$product->id}", [
                'category_id' => $product->category_id,
                'name' => 'Customer Product',
            ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $product->name,
        ]);
    }

    public function test_guest_cannot_update_a_product_via_api(): void
    {
        $product = Product::factory()->create();

        $response = $this->putJson("/api/v1/seller/products/{$product->id}", [
            'category_id' => $product->category_id,
            'name' => 'Guest Product',
        ]);

        $response->assertUnauthorized();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $product->name,
        ]);
    }

    public function test_update_product_api_requires_a_valid_name(): void
    {
        $seller = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $store->id,
            'name' => 'Original Product',
        ]);

        $this->assignSellerRole($seller);

        $response = $this->actingAs($seller, 'sanctum')
            ->putJson("/api/v1/seller/products/{$product->id}", [
                'category_id' => $product->category_id,
                'name' => 'ab',
            ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('name');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Original Product',
        ]);
    }

    public function test_seller_can_delete_their_product_via_api(): void
    {
        $seller = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $store->id,
        ]);

        $this->assignSellerRole($seller);

        $response = $this->actingAs($seller, 'sanctum')
            ->deleteJson("/api/v1/seller/products/{$product->id}");

        $response
            ->assertOk()
            ->assertJson([
                'message' => 'Product deleted successfully.',
            ]);

        $this->assertSoftDeleted('products', [
            'id' => $product->id,
        ]);
    }

    public function test_seller_cannot_delete_another_sellers_product_via_api(): void
    {
        $seller = User::factory()->create();

        SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

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

        $this->assignSellerRole($seller);

        $response = $this->actingAs($seller, 'sanctum')
            ->deleteJson("/api/v1/seller/products/{$product->id}");

        $response->assertForbidden();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $product->name,
            'deleted_at' => null,
        ]);
    }

    public function test_customer_cannot_delete_a_product_via_api(): void
    {
        $customer = User::factory()->create();

        $product = Product::factory()->create();

        $response = $this->actingAs($customer, 'sanctum')
            ->deleteJson("/api/v1/seller/products/{$product->id}");

        $response->assertForbidden();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'deleted_at' => null,
        ]);
    }

    public function test_guest_cannot_delete_a_product_via_api(): void
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/v1/seller/products/{$product->id}");

        $response->assertUnauthorized();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'deleted_at' => null,
        ]);
    }

    public function test_update_product_api_ignores_protected_fields(): void
    {
        $seller = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $category = Category::factory()->create();

        $product = Product::factory()->create([
            'store_id' => $store->id,
            'category_id' => $category->id,
            'name' => 'Original Product',
            'slug' => 'original-product',
        ]);

        $this->assignSellerRole($seller);

        $response = $this->actingAs($seller, 'sanctum')
            ->putJson("/api/v1/seller/products/{$product->id}", [
                'category_id' => $category->id,
                'name' => 'Updated Product',
                'description' => 'Updated description',
                'store_id' => 999,
                'slug' => 'hacked-slug',
                'status' => 'active',
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $product->id)
            ->assertJsonPath('data.store_id', $store->id)
            ->assertJsonPath('data.category_id', $category->id)
            ->assertJsonPath('data.name', 'Updated Product')
            ->assertJsonPath('data.slug', 'original-product')
            ->assertJsonPath('data.description', 'Updated description')
            ->assertJsonPath('data.status', $product->status->value);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'store_id' => $store->id,
            'category_id' => $category->id,
            'name' => 'Updated Product',
            'slug' => 'original-product',
            'description' => 'Updated description',
            'status' => $product->status->value,
        ]);
    }

    private function assignSellerRole(User $seller): void
    {
        Role::findOrCreate('seller', 'web');

        $seller->assignRole('seller');
    }
}
