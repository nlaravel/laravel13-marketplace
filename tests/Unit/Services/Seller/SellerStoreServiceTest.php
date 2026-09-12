<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Seller;

use App\Exceptions\SellerException;
use App\Models\SellerProfile;
use App\Models\Store;
use App\Models\User;
use App\Services\Seller\SellerStoreService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerStoreServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_only_the_sellers_stores(): void
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

        $service = app(SellerStoreService::class);

        $stores = $service->getStores($seller->id);

        $this->assertCount(2, $stores);
        $this->assertTrue($stores->every(fn (Store $store): bool => $store->seller_id === $sellerProfile->id));
    }

    public function test_it_returns_the_sellers_store(): void
    {
        $seller = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $service = app(SellerStoreService::class);

        $result = $service->getStore($seller->id, $store->id);

        $this->assertTrue($result->is($store));
    }

    public function test_seller_cannot_access_another_sellers_store(): void
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
        ]);

        $service = app(SellerStoreService::class);

        $this->expectException(ModelNotFoundException::class);

        $service->getStore($seller->id, $store->id);
    }

    public function test_it_creates_a_pending_store_for_the_seller(): void
    {
        $seller = User::factory()->create();

        SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $service = app(SellerStoreService::class);

        $store = $service->createStore($seller->id, [
            'name' => 'My Test Store',
            'description' => 'Test store description',
        ]);

        $this->assertDatabaseHas('stores', [
            'id' => $store->id,
            'seller_id' => $store->seller_id,
            'name' => 'My Test Store',
            'slug' => 'my-test-store',
            'description' => 'Test store description',
            'status' => 'pending',
            'approved_at' => null,
            'approved_by' => null,
            'rejection_reason' => null,
        ]);

        $this->assertSame('my-test-store', $store->slug);
        $this->assertSame('pending', $store->status->value);
    }

    public function test_it_throws_seller_exception_when_seller_profile_does_not_exist(): void
    {
        $user = User::factory()->create();

        $service = app(SellerStoreService::class);

        $this->expectException(SellerException::class);

        $service->createStore($user->id, [
            'name' => 'My Test Store',
            'description' => 'Test store description',
        ]);
    }

    public function test_seller_can_update_their_store(): void
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

        $service = app(SellerStoreService::class);

        $updatedStore = $service->updateStore($seller->id, $store->id, [
            'name' => 'Updated Store Name',
            'description' => 'Updated description',
        ]);

        $this->assertSame($store->id, $updatedStore->id);
        $this->assertSame('Updated Store Name', $updatedStore->name);
        $this->assertSame('Updated description', $updatedStore->description);

        $this->assertDatabaseHas('stores', [
            'id' => $store->id,
            'seller_id' => $sellerProfile->id,
            'name' => 'Updated Store Name',
            'description' => 'Updated description',
        ]);
    }

    public function test_seller_cannot_update_another_sellers_store(): void
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
        ]);

        $service = app(SellerStoreService::class);

        $this->expectException(ModelNotFoundException::class);

        try {
            $service->updateStore($seller->id, $store->id, [
                'name' => 'Hacked Store',
                'description' => 'Should not be updated',
            ]);
        } finally {
            $this->assertDatabaseHas('stores', [
                'id' => $store->id,
                'name' => 'Original Store',
            ]);
        }
    }

    public function test_it_generates_a_unique_slug_for_duplicate_store_names(): void
    {
        $user = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        Store::factory()->create([
            'seller_id' => $sellerProfile->id,
            'name' => 'My Store',
            'slug' => 'my-store',
        ]);

        $service = app(SellerStoreService::class);

        $store = $service->createStore($user->id, [
            'name' => 'My Store',
            'description' => 'Another store',
        ]);

        $this->assertSame('my-store-2', $store->slug);
    }
}
