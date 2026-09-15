<?php

declare(strict_types=1);

namespace Tests\Feature\Seller\Products;

use App\Enums\ProductStatus;
use App\Enums\StoreStatus;
use App\Livewire\Seller\Products\Edit;
use App\Models\Category;
use App\Models\Product;
use App\Models\SellerProfile;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class EditTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::findOrCreate('seller');
    }

    public function test_product_owner_can_access_edit_page(): void
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

        $product = Product::factory()->create([
            'store_id' => $store->id,
        ]);

        $this->actingAs($user)
            ->get(route('seller.product.edit', $product))
            ->assertOk()
            ->assertSeeLivewire(Edit::class)
            ->assertSee($product->name);
    }

    public function test_another_seller_cannot_access_edit_page(): void
    {
        $owner = User::factory()->create();
        $owner->assignRole('seller');

        $ownerProfile = SellerProfile::factory()->create([
            'user_id' => $owner->id,
        ]);

        $ownerStore = Store::factory()->create([
            'seller_id' => $ownerProfile->id,
            'status' => StoreStatus::APPROVED,
        ]);

        $product = Product::factory()->create([
            'store_id' => $ownerStore->id,
        ]);

        $anotherSeller = User::factory()->create();
        $anotherSeller->assignRole('seller');

        SellerProfile::factory()->create([
            'user_id' => $anotherSeller->id,
        ]);

        $this->actingAs($anotherSeller)
            ->get(route('seller.product.edit', $product))
            ->assertForbidden();
    }

    public function test_customer_cannot_access_edit_page(): void
    {
        $owner = User::factory()->create();
        $owner->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $owner->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
            'status' => StoreStatus::APPROVED,
        ]);

        $product = Product::factory()->create([
            'store_id' => $store->id,
        ]);

        $customer = User::factory()->create();

        $this->actingAs($customer)
            ->get(route('seller.product.edit', $product))
            ->assertForbidden();
    }

    public function test_product_owner_can_update_product(): void
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

        $product = Product::factory()->create([
            'store_id' => $store->id,
        ]);

        $category = Category::factory()->create();

        Livewire::actingAs($user)
            ->test(Edit::class, [
                'product' => $product,
            ])
            ->set('categoryId', $category->id)
            ->set('name', 'Updated Product')
            ->set('description', 'Updated description')
            ->call('save')
            ->assertRedirect(route('seller.product.index'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'category_id' => $category->id,
            'name' => 'Updated Product',
            'description' => 'Updated description',
        ]);
    }

    public function test_updating_product_does_not_change_its_status(): void
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

        $product = Product::factory()->create([
            'store_id' => $store->id,
            'status' => ProductStatus::ACTIVE,
        ]);

        $category = Category::factory()->create();

        Livewire::actingAs($user)
            ->test(Edit::class, [
                'product' => $product,
            ])
            ->set('categoryId', $category->id)
            ->set('name', 'Updated Active Product')
            ->set('description', 'Updated description')
            ->call('save')
            ->assertRedirect(route('seller.product.index'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'status' => ProductStatus::ACTIVE,
            'name' => 'Updated Active Product',
        ]);
    }
}
