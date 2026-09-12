<?php

declare(strict_types=1);

namespace Tests\Feature\Seller;

use App\Enums\StoreStatus;
use App\Livewire\Seller\CreateStore;
use App\Livewire\Seller\EditStore;
use App\Models\SellerProfile;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_can_access_store_page(): void
    {
        $seller = User::factory()->create();

        Role::findOrCreate('seller', 'web');

        $seller->assignRole('seller');

        $response = $this->actingAs($seller)
            ->get(route('seller.store'));

        $response->assertOk();
    }

    public function test_seller_can_see_only_their_own_stores(): void
    {
        $seller = User::factory()->create();

        Role::findOrCreate('seller', 'web');

        $seller->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $ownStore = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $otherSeller = User::factory()->create();

        $otherSellerProfile = SellerProfile::factory()->create([
            'user_id' => $otherSeller->id,
        ]);

        $otherStore = Store::factory()->create([
            'seller_id' => $otherSellerProfile->id,
        ]);

        $response = $this->actingAs($seller)
            ->get(route('seller.store'));

        $response->assertOk();

        $response->assertSee($ownStore->name);
        $response->assertDontSee($otherStore->name);
    }

    public function test_customer_cannot_access_store_page(): void
    {
        $customer = User::factory()->create();

        $response = $this->actingAs($customer)
            ->get(route('seller.store'));

        $response->assertForbidden();
    }

    public function test_seller_can_access_create_store_page(): void
    {
        $seller = User::factory()->create();

        Role::findOrCreate('seller', 'web');

        $seller->assignRole('seller');

        $response = $this->actingAs($seller)
            ->get(route('seller.store.create'));

        $response->assertOk();
    }

    public function test_seller_can_create_a_store(): void
    {
        $seller = User::factory()->create();

        Role::findOrCreate('seller', 'web');

        $seller->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        Livewire::actingAs($seller)
            ->test(CreateStore::class)
            ->set('name', 'My Test Store')
            ->set('description', 'Test store description')
            ->call('save')
            ->assertRedirect(route('seller.store'));

        $this->assertDatabaseHas('stores', [
            'seller_id' => $sellerProfile->id,
            'name' => 'My Test Store',
            'slug' => 'my-test-store',
            'description' => 'Test store description',
            'status' => 'pending',
            'approved_at' => null,
            'approved_by' => null,
            'rejection_reason' => null,
        ]);
    }

    public function test_create_store_requires_a_valid_name(): void
    {
        $seller = User::factory()->create();

        Role::findOrCreate('seller', 'web');

        $seller->assignRole('seller');

        SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        Livewire::actingAs($seller)
            ->test(CreateStore::class)
            ->set('name', 'ab')
            ->set('description', 'Test store description')
            ->call('save')
            ->assertHasErrors([
                'name' => 'min',
            ]);

        $this->assertDatabaseCount('stores', 0);
    }

    public function test_customer_cannot_access_create_store_page(): void
    {
        $customer = User::factory()->create();

        $response = $this->actingAs($customer)
            ->get(route('seller.store.create'));

        $response->assertForbidden();
    }

    public function test_seller_can_access_edit_store_page(): void
    {
        $seller = User::factory()->create();

        Role::findOrCreate('seller', 'web');

        $seller->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
            'name' => 'Original Store',
            'description' => 'Original description',
        ]);

        $response = $this->actingAs($seller)
            ->get(route('seller.store.edit', $store->id));

        $response->assertOk();
        $response->assertSee('Edit Store');
        $response->assertSee('Original Store');
    }

    public function test_seller_can_update_their_store(): void
    {
        $seller = User::factory()->create();

        Role::findOrCreate('seller', 'web');

        $seller->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
            'name' => 'Original Store',
            'description' => 'Original description',
            'status' => StoreStatus::PENDING,
        ]);

        Livewire::actingAs($seller)
            ->test(EditStore::class, [
                'store' => $store,
            ])
            ->set('name', 'Updated Store')
            ->set('description', 'Updated description')
            ->call('save')
            ->assertRedirect(route('seller.store'));

        $this->assertDatabaseHas('stores', [
            'id' => $store->id,
            'name' => 'Updated Store',
            'description' => 'Updated description',
            'slug' => $store->slug,
            'status' => StoreStatus::PENDING->value,
        ]);
    }

    public function test_seller_cannot_edit_another_sellers_store(): void
    {
        $seller = User::factory()->create();
        $otherSeller = User::factory()->create();

        Role::findOrCreate('seller', 'web');

        $seller->assignRole('seller');
        $otherSeller->assignRole('seller');

        $otherSellerProfile = SellerProfile::factory()->create([
            'user_id' => $otherSeller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $otherSellerProfile->id,
        ]);

        $response = $this->actingAs($seller)
            ->get(route('seller.store.edit', $store->id));

        $response->assertNotFound();
    }

    public function test_customer_cannot_access_edit_store_page(): void
    {
        $customer = User::factory()->create();

        $seller = User::factory()->create();

        Role::findOrCreate('seller', 'web');

        $seller->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $response = $this->actingAs($customer)
            ->get(route('seller.store.edit', $store->id));

        $response->assertForbidden();
    }

    public function test_guest_cannot_access_edit_store_page(): void
    {
        $seller = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $response = $this->get(route('seller.store.edit', $store->id));

        $response->assertRedirect(route('login'));
    }

    public function test_edit_store_requires_a_valid_name(): void
    {
        $seller = User::factory()->create();

        Role::findOrCreate('seller', 'web');

        $seller->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        Livewire::actingAs($seller)
            ->test(EditStore::class, [
                'store' => $store,
            ])
            ->set('name', 'ab')
            ->set('description', 'Updated description')
            ->call('save')
            ->assertHasErrors([
                'name' => 'min',
            ]);

        $this->assertDatabaseMissing('stores', [
            'id' => $store->id,
            'name' => 'ab',
        ]);
    }

    public function test_edit_store_does_not_change_protected_fields(): void
    {
        $seller = User::factory()->create();

        Role::findOrCreate('seller', 'web');

        $seller->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
            'status' => StoreStatus::PENDING,
        ]);

        $originalSlug = $store->slug;
        $originalSellerId = $store->seller_id;
        $originalStatus = $store->status->value;

        Livewire::actingAs($seller)
            ->test(EditStore::class, [
                'store' => $store,
            ])
            ->set('name', 'Security Updated Store')
            ->set('description', 'Security test')
            ->call('save');

        $this->assertDatabaseHas('stores', [
            'id' => $store->id,
            'seller_id' => $originalSellerId,
            'slug' => $originalSlug,
            'status' => $originalStatus,
        ]);
    }
}
