<?php

declare(strict_types=1);

namespace Tests\Feature\Seller\Products;

use App\Enums\StoreStatus;
use App\Livewire\Seller\Products\Index;
use App\Models\Product;
use App\Models\SellerProfile;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::findOrCreate('seller');
    }

    public function test_seller_can_view_products_page(): void
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
            ->get(route('seller.product.index'))
            ->assertOk()
            ->assertSeeLivewire(Index::class)
            ->assertSee($product->name);
    }

    public function test_seller_only_sees_their_own_products(): void
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $sellerStore = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
            'status' => StoreStatus::APPROVED,
        ]);

        $otherSeller = User::factory()->create();
        $otherSeller->assignRole('seller');

        $otherSellerProfile = SellerProfile::factory()->create([
            'user_id' => $otherSeller->id,
        ]);

        $otherStore = Store::factory()->create([
            'seller_id' => $otherSellerProfile->id,
            'status' => StoreStatus::APPROVED,
        ]);

        $ownProduct = Product::factory()->create([
            'store_id' => $sellerStore->id,
        ]);

        $otherProduct = Product::factory()->create([
            'store_id' => $otherStore->id,
        ]);

        Livewire::actingAs($seller)
            ->test(Index::class)
            ->assertSee($ownProduct->name)
            ->assertDontSee($otherProduct->name);
    }

    public function test_customer_cannot_access_products_page(): void
    {
        $customer = User::factory()->create();

        $this->actingAs($customer)
            ->get(route('seller.product.index'))
            ->assertForbidden();
    }
}
