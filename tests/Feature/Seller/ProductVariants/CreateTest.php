<?php

declare(strict_types=1);

namespace Tests\Feature\Seller\ProductVariants;

use App\Enums\ProductStatus;
use App\Enums\StoreStatus;
use App\Livewire\Seller\ProductVariants\Create;
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

class CreateTest extends TestCase
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

    private function createSeller(): User
    {
        $user = User::factory()->create();

        $user->assignRole('seller');

        SellerProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        return $user;
    }

    private function createProduct(User $seller): Product
    {
        $sellerProfile = $seller->sellerProfile;

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
            'status' => StoreStatus::APPROVED,
        ]);

        return Product::factory()->create([
            'store_id' => $store->id,
            'category_id' => Category::factory()->create()->id,
            'status' => ProductStatus::DRAFT,
        ]);
    }

    public function test_seller_can_render_create_variant_page(): void
    {
        $seller = $this->createSeller();
        $product = $this->createProduct($seller);

        Livewire::actingAs($seller)
            ->test(Create::class, [
                'product' => $product,
            ])
            ->assertStatus(200)
            ->assertSee('Add Product Variant')
            ->assertSet('sku', '')
            ->assertSet('price', '')
            ->assertSet('compareAtPrice', '')
            ->assertSet('isActive', true);
    }

    public function test_seller_can_create_variant(): void
    {
        $seller = $this->createSeller();
        $product = $this->createProduct($seller);

        Livewire::actingAs($seller)
            ->test(Create::class, [
                'product' => $product,
            ])
            ->set('sku', 'SKU-001')
            ->set('price', '49.99')
            ->set('compareAtPrice', '59.99')
            ->set('isActive', true)
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('show-success', message: 'Product variant created successfully.')
            ->assertRedirectToRoute('seller.product.variants.index', ['product' => $product->id]);

        $this->assertDatabaseHas('product_variants', [
            'product_id' => $product->id,
            'sku' => 'SKU-001',
            'price' => '49.99',
            'compare_at_price' => '59.99',
            'is_active' => true,
        ]);
    }

    public function test_seller_can_create_variant_without_compare_at_price(): void
    {
        $seller = $this->createSeller();
        $product = $this->createProduct($seller);

        Livewire::actingAs($seller)
            ->test(Create::class, [
                'product' => $product,
            ])
            ->set('sku', 'SKU-002')
            ->set('price', '39.99')
            ->set('compareAtPrice', '')
            ->set('isActive', true)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirectToRoute('seller.product.variants.index', ['product' => $product->id]);

        $this->assertDatabaseHas('product_variants', [
            'product_id' => $product->id,
            'sku' => 'SKU-002',
            'price' => '39.99',
            'compare_at_price' => null,
            'is_active' => true,
        ]);
    }

    public function test_sku_is_required(): void
    {
        $seller = $this->createSeller();
        $product = $this->createProduct($seller);

        Livewire::actingAs($seller)
            ->test(Create::class, [
                'product' => $product,
            ])
            ->set('price', '49.99')
            ->call('save')
            ->assertHasErrors([
                'sku' => ['required'],
            ]);
    }

    public function test_price_is_required(): void
    {
        $seller = $this->createSeller();
        $product = $this->createProduct($seller);

        Livewire::actingAs($seller)
            ->test(Create::class, [
                'product' => $product,
            ])
            ->set('sku', 'SKU-003')
            ->call('save')
            ->assertHasErrors([
                'price' => ['required'],
            ]);
    }

    public function test_compare_at_price_must_be_greater_than_or_equal_to_price(): void
    {
        $seller = $this->createSeller();
        $product = $this->createProduct($seller);

        Livewire::actingAs($seller)
            ->test(Create::class, [
                'product' => $product,
            ])
            ->set('sku', 'SKU-004')
            ->set('price', '50.00')
            ->set('compareAtPrice', '40.00')
            ->call('save')
            ->assertHasErrors([
                'compareAtPrice' => ['gte:price'],
            ]);
    }

    public function test_duplicate_sku_is_rejected_by_validation(): void
    {
        $seller = $this->createSeller();
        $product = $this->createProduct($seller);

        ProductVariant::factory()->create([
            'product_id' => $product->id,
            'sku' => 'SKU-EXISTING',
        ]);

        Livewire::actingAs($seller)
            ->test(Create::class, [
                'product' => $product,
            ])
            ->set('sku', 'SKU-EXISTING')
            ->set('price', '49.99')
            ->call('save')
            ->assertHasErrors([
                'sku' => ['unique'],
            ]);
    }

    public function test_seller_without_seller_profile_cannot_create_variant(): void
    {
        $user = User::factory()->create();
        $user->assignRole('seller');

        $product = Product::factory()->create();

        Livewire::actingAs($user)
            ->test(Create::class, [
                'product' => $product,
            ])
            ->assertForbidden();
    }

    public function test_seller_cannot_create_variant_for_another_sellers_product(): void
    {
        $seller = $this->createSeller();

        $otherSeller = $this->createSeller();
        $product = $this->createProduct($otherSeller);

        Livewire::actingAs($seller)
            ->test(Create::class, [
                'product' => $product,
            ])
            ->assertForbidden();
    }
}
