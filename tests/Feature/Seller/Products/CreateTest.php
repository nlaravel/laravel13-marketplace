<?php

declare(strict_types=1);

namespace Tests\Feature\Seller\Products;

use App\Enums\StoreStatus;
use App\Livewire\Seller\Products\Create;
use App\Models\Category;
use App\Models\SellerProfile;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::findOrCreate('seller');
    }

    public function test_seller_with_approved_store_can_access_create_page(): void
    {
        $user = User::factory()->create();
        $user->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        Store::factory()->create([
            'seller_id' => $sellerProfile->id,
            'status' => StoreStatus::APPROVED,
        ]);

        $this->actingAs($user)
            ->get(route('seller.product.create'))
            ->assertOk()
            ->assertSeeLivewire(Create::class);
    }

    public function test_seller_without_seller_profile_cannot_access_create_page(): void
    {
        $user = User::factory()->create();
        $user->assignRole('seller');

        $this->actingAs($user)
            ->get(route('seller.product.create'))
            ->assertForbidden();
    }

    public function test_seller_without_approved_store_cannot_access_create_page(): void
    {
        $user = User::factory()->create();
        $user->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        Store::factory()->create([
            'seller_id' => $sellerProfile->id,
            'status' => StoreStatus::PENDING,
        ]);

        $this->actingAs($user)
            ->get(route('seller.product.create'))
            ->assertForbidden();
    }

    public function test_customer_cannot_access_create_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('seller.product.create'))
            ->assertForbidden();
    }

    public function test_seller_can_create_product(): void
    {
        $user = User::factory()->create();
        $user->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
            'status' => StoreStatus::APPROVED,
        ]);

        $category = Category::factory()->create();

        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('storeId', $store->id)
            ->set('categoryId', $category->id)
            ->set('name', 'Test Product')
            ->set('description', 'Test product description')
            ->call('save')
            ->assertRedirect(route('seller.product.index'));

        $this->assertDatabaseHas('products', [
            'store_id' => $store->id,
            'category_id' => $category->id,
            'name' => 'Test Product',
            'description' => 'Test product description',
        ]);
    }
}
