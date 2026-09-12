<?php

declare(strict_types=1);

namespace Tests\Feature\Seller;

use App\Models\SellerProfile;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SellerStoreControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_can_get_their_stores(): void
    {
        $seller = User::factory()->create();
        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $otherSeller = User::factory()->create();
        $otherSellerProfile = SellerProfile::factory()->create([
            'user_id' => $otherSeller->id,
        ]);

        Store::factory()->create([
            'seller_id' => $otherSellerProfile->id,
        ]);

        Role::findOrCreate('seller', 'web');
        $seller->assignRole('seller');

        $response = $this->actingAs($seller, 'sanctum')
            ->getJson('/api/v1/seller/stores');

        $response
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_seller_cannot_see_another_sellers_store(): void
    {
        $seller = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $otherSeller = User::factory()->create();

        $otherSellerProfile = SellerProfile::factory()->create([
            'user_id' => $otherSeller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $otherSellerProfile->id,
        ]);

        Role::findOrCreate('seller', 'web');
        $seller->assignRole('seller');

        $response = $this->actingAs($seller, 'sanctum')
            ->getJson("/api/v1/seller/stores/{$store->id}");

        $response->assertNotFound();
    }

    public function test_seller_can_get_their_store(): void
    {
        $seller = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        Role::findOrCreate('seller', 'web');
        $seller->assignRole('seller');

        $response = $this->actingAs($seller, 'sanctum')
            ->getJson("/api/v1/seller/stores/{$store->id}");

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $store->id)
            ->assertJsonPath('data.name', $store->name)
            ->assertJsonPath('data.slug', $store->slug);
    }

    public function test_customer_cannot_access_seller_stores_api(): void
    {
        $customer = User::factory()->create();

        $response = $this->actingAs($customer, 'sanctum')
            ->getJson('/api/v1/seller/stores');

        $response->assertForbidden();
    }

    public function test_guest_cannot_access_seller_stores_api(): void
    {
        $response = $this->getJson('/api/v1/seller/stores');

        $response->assertUnauthorized();
    }

    public function test_seller_can_create_a_store_via_api(): void
    {
        $seller = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        Role::findOrCreate('seller', 'web');
        $seller->assignRole('seller');

        $response = $this->actingAs($seller, 'sanctum')
            ->postJson('/api/v1/seller/stores', [
                'name' => 'My API Store',
                'description' => 'Store created through API',
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.name', 'My API Store')
            ->assertJsonPath('data.slug', 'my-api-store')
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('stores', [
            'seller_id' => $sellerProfile->id,
            'name' => 'My API Store',
            'slug' => 'my-api-store',
            'description' => 'Store created through API',
            'status' => 'pending',
        ]);
    }

    public function test_customer_cannot_create_a_store_via_api(): void
    {
        $customer = User::factory()->create();

        $response = $this->actingAs($customer, 'sanctum')
            ->postJson('/api/v1/seller/stores', [
                'name' => 'Customer Store',
            ]);

        $response->assertForbidden();

        $this->assertDatabaseCount('stores', 0);
    }

    public function test_guest_cannot_create_a_store_via_api(): void
    {
        $response = $this->postJson('/api/v1/seller/stores', [
            'name' => 'Guest Store',
        ]);

        $response->assertUnauthorized();

        $this->assertDatabaseCount('stores', 0);
    }

    public function test_create_store_api_requires_a_valid_name(): void
    {
        $seller = User::factory()->create();

        SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        Role::findOrCreate('seller', 'web');
        $seller->assignRole('seller');

        $response = $this->actingAs($seller, 'sanctum')
            ->postJson('/api/v1/seller/stores', [
                'name' => 'ab',
                'description' => 'Invalid store',
            ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('name');

        $this->assertDatabaseCount('stores', 0);
    }

    public function test_seller_can_update_their_store_via_api(): void
    {
        $seller = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
            'name' => 'Old Store Name',
            'description' => 'Old description',
        ]);

        Role::findOrCreate('seller', 'web');
        $seller->assignRole('seller');

        $response = $this->actingAs($seller, 'sanctum')
            ->putJson("/api/v1/seller/stores/{$store->id}", [
                'name' => 'Updated Store Name',
                'description' => 'Updated description',
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $store->id)
            ->assertJsonPath('data.name', 'Updated Store Name')
            ->assertJsonPath('data.slug', $store->slug)
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('stores', [
            'id' => $store->id,
            'seller_id' => $sellerProfile->id,
            'name' => 'Updated Store Name',
            'description' => 'Updated description',
            'slug' => $store->slug,
            'status' => 'pending',
        ]);
    }

    public function test_seller_cannot_update_another_sellers_store_via_api(): void
    {
        $seller = User::factory()->create();

        SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $otherSeller = User::factory()->create();

        $otherSellerProfile = SellerProfile::factory()->create([
            'user_id' => $otherSeller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $otherSellerProfile->id,
            'name' => 'Original Store',
            'description' => 'Original description',
        ]);

        Role::findOrCreate('seller', 'web');
        $seller->assignRole('seller');

        $response = $this->actingAs($seller, 'sanctum')
            ->putJson("/api/v1/seller/stores/{$store->id}", [
                'name' => 'Hacked Store',
                'description' => 'Should not be updated',
            ]);

        $response->assertNotFound();

        $this->assertDatabaseHas('stores', [
            'id' => $store->id,
            'seller_id' => $otherSellerProfile->id,
            'name' => 'Original Store',
            'description' => 'Original description',
        ]);
    }

    public function test_customer_cannot_update_a_store_via_api(): void
    {
        $customer = User::factory()->create();

        $seller = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $response = $this->actingAs($customer, 'sanctum')
            ->putJson("/api/v1/seller/stores/{$store->id}", [
                'name' => 'Customer Store',
            ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('stores', [
            'id' => $store->id,
            'name' => $store->name,
        ]);
    }

    public function test_guest_cannot_update_a_store_via_api(): void
    {
        $store = Store::factory()->create();

        $response = $this->putJson("/api/v1/seller/stores/{$store->id}", [
            'name' => 'Guest Store',
        ]);

        $response->assertUnauthorized();

        $this->assertDatabaseHas('stores', [
            'id' => $store->id,
            'name' => $store->name,
        ]);
    }

    public function test_update_store_api_requires_a_valid_name(): void
    {
        $seller = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
            'name' => 'Original Store',
        ]);

        Role::findOrCreate('seller', 'web');
        $seller->assignRole('seller');

        $response = $this->actingAs($seller, 'sanctum')
            ->putJson("/api/v1/seller/stores/{$store->id}", [
                'name' => 'ab',
                'description' => 'Invalid update',
            ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('name');

        $this->assertDatabaseHas('stores', [
            'id' => $store->id,
            'name' => 'Original Store',
        ]);
    }

    public function test_update_store_api_ignores_protected_fields(): void
    {
        $seller = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
            'name' => 'Original Store',
            'description' => 'Original description',
            'status' => 'pending',
            'approved_at' => null,
            'approved_by' => null,
            'rejection_reason' => null,
        ]);

        Role::findOrCreate('seller', 'web');
        $seller->assignRole('seller');

        $response = $this->actingAs($seller, 'sanctum')
            ->putJson("/api/v1/seller/stores/{$store->id}", [
                'name' => 'Updated Store',
                'description' => 'Updated description',
                'seller_id' => 999,
                'slug' => 'hacked-slug',
                'status' => 'approved',
                'approved_at' => '2026-09-12T00:00:00Z',
                'approved_by' => 999,
                'rejection_reason' => 'Hacked',
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.name', 'Updated Store')
            ->assertJsonPath('data.description', 'Updated description')
            ->assertJsonPath('data.slug', $store->slug)
            ->assertJsonPath('data.status', 'pending')
            ->assertJsonPath('data.approved_at', null)
            ->assertJsonPath('data.approved_by', null)
            ->assertJsonPath('data.rejection_reason', null);

        $this->assertDatabaseHas('stores', [
            'id' => $store->id,
            'seller_id' => $sellerProfile->id,
            'name' => 'Updated Store',
            'description' => 'Updated description',
            'slug' => $store->slug,
            'status' => 'pending',
            'approved_at' => null,
            'approved_by' => null,
            'rejection_reason' => null,
        ]);
    }
}
