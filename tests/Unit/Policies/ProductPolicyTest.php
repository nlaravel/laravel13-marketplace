<?php

declare(strict_types=1);

namespace Tests\Unit\Policies;

use App\Enums\StoreStatus;
use App\Models\Product;
use App\Models\SellerProfile;
use App\Models\Store;
use App\Models\User;
use App\Policies\ProductPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_with_an_approved_store_can_create_products(): void
    {
        $user = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        Store::factory()->create([
            'seller_id' => $sellerProfile->id,
            'status' => StoreStatus::APPROVED,
        ]);

        $policy = new ProductPolicy;

        $this->assertTrue($policy->create($user));
    }

    public function test_seller_without_an_approved_store_cannot_create_products(): void
    {
        $user = User::factory()->create();

        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        Store::factory()->create([
            'seller_id' => $sellerProfile->id,
            'status' => StoreStatus::PENDING,
        ]);

        $policy = new ProductPolicy;

        $this->assertFalse($policy->create($user));
    }

    public function test_product_owner_can_view_their_product(): void
    {
        [$user, $product] = $this->createSellerProduct();

        $policy = new ProductPolicy;

        $this->assertTrue($policy->view($user, $product));
    }

    public function test_another_seller_cannot_view_the_product(): void
    {
        [, $product] = $this->createSellerProduct();

        $anotherUser = User::factory()->create();

        $this->assertFalse((new ProductPolicy)->view($anotherUser, $product));
    }

    public function test_product_owner_can_update_their_product(): void
    {
        [$user, $product] = $this->createSellerProduct();

        $this->assertTrue((new ProductPolicy)->update($user, $product));
    }

    public function test_another_seller_cannot_update_the_product(): void
    {
        [, $product] = $this->createSellerProduct();

        $anotherUser = User::factory()->create();

        $this->assertFalse((new ProductPolicy)->update($anotherUser, $product));
    }

    public function test_product_owner_can_delete_their_product(): void
    {
        [$user, $product] = $this->createSellerProduct();

        $this->assertTrue((new ProductPolicy)->delete($user, $product));
    }

    public function test_another_seller_cannot_delete_the_product(): void
    {
        [, $product] = $this->createSellerProduct();

        $anotherUser = User::factory()->create();

        $this->assertFalse((new ProductPolicy)->delete($anotherUser, $product));
    }

    private function createSellerProduct(): array
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

        return [$user, $product];
    }
}
