<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Seller\ProductVariants;

use App\Enums\ProductStatus;
use App\Enums\StoreStatus;
use App\Livewire\Seller\ProductVariants\Index;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SellerProfile;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate([
            'name' => 'seller',
            'guard_name' => 'web',
        ]);

        $this->app
            ->make(PermissionRegistrar::class)
            ->forgetCachedPermissions();
    }

    public function test_seller_can_render_product_variants_index_page(): void
    {
        $user = $this->createSeller();
        $product = $this->createProduct($user);

        Livewire::actingAs($user)
            ->test(Index::class, [
                'product' => $product,
            ])
            ->assertStatus(200);
    }

    public function test_seller_can_see_product_variants(): void
    {
        $user = $this->createSeller();
        $product = $this->createProduct($user);

        ProductVariant::factory()->create([
            'product_id' => $product->id,
            'sku' => 'SKU-001',
            'price' => 100,
            'compare_at_price' => 120,
            'is_active' => true,
        ]);

        ProductVariant::factory()->create([
            'product_id' => $product->id,
            'sku' => 'SKU-002',
            'price' => 150,
            'compare_at_price' => null,
            'is_active' => false,
        ]);

        Livewire::actingAs($user)
            ->test(Index::class, [
                'product' => $product,
            ])
            ->assertSee('SKU-001')
            ->assertSee('SKU-002')
            ->assertSee('100.00')
            ->assertSee('150.00');
    }

    public function test_seller_cannot_view_another_sellers_product_variants(): void
    {
        $owner = $this->createSeller();
        $otherSeller = $this->createSeller();

        $product = $this->createProduct($owner);

        ProductVariant::factory()->create([
            'product_id' => $product->id,
            'sku' => 'OWNER-SKU-001',
            'price' => 100,
            'is_active' => true,
        ]);

        Livewire::actingAs($otherSeller)
            ->test(Index::class, [
                'product' => $product,
            ])
            ->assertForbidden();
    }

    public function test_seller_only_sees_variants_belonging_to_the_selected_product(): void
    {
        $user = $this->createSeller();

        $product = $this->createProduct($user);
        $anotherProduct = $this->createProduct($user);

        ProductVariant::factory()->create([
            'product_id' => $product->id,
            'sku' => 'PRODUCT-ONE-SKU',
            'price' => 100,
            'is_active' => true,
        ]);

        ProductVariant::factory()->create([
            'product_id' => $anotherProduct->id,
            'sku' => 'PRODUCT-TWO-SKU',
            'price' => 200,
            'is_active' => true,
        ]);

        Livewire::actingAs($user)
            ->test(Index::class, [
                'product' => $product,
            ])
            ->assertSee('PRODUCT-ONE-SKU')
            ->assertDontSee('PRODUCT-TWO-SKU');
    }

    private function createSeller(): User
    {
        $user = User::factory()->create();

        $user->assignRole('seller');

        SellerProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        return $user;
    }

    private function createProduct(User $user): Product
    {
        $sellerProfile = SellerProfile::query()
            ->where('user_id', $user->id)
            ->firstOrFail();

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
            'status' => StoreStatus::APPROVED,
        ]);

        $category = Category::factory()->create();

        return Product::factory()->create([
            'store_id' => $store->id,
            'category_id' => $category->id,
            'status' => ProductStatus::DRAFT,
        ]);
    }
}
