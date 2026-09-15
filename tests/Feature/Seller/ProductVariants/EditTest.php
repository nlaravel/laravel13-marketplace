<?php

declare(strict_types=1);

namespace Tests\Feature\Seller\ProductVariants;

use App\Enums\ProductStatus;
use App\Enums\StoreStatus;
use App\Livewire\Seller\ProductVariants\Edit;
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

class EditTest extends TestCase
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

    private function createVariant(Product $product, array $attributes = []): ProductVariant
    {
        return ProductVariant::factory()->create(array_merge([
            'product_id' => $product->id,
            'sku' => 'SKU-001',
            'price' => '49.99',
            'compare_at_price' => '59.99',
            'is_active' => true,
        ], $attributes));
    }

    public function test_seller_can_render_edit_variant_page(): void
    {
        $seller = $this->createSeller();
        $product = $this->createProduct($seller);

        $variant = $this->createVariant($product);

        Livewire::actingAs($seller)
            ->test(Edit::class, [
                'product' => $product,
                'variant' => $variant,
            ])
            ->assertStatus(200)
            ->assertSet('sku', 'SKU-001')
            ->assertSet('price', '49.99')
            ->assertSet('compareAtPrice', '59.99')
            ->assertSet('isActive', true);
    }

    public function test_seller_can_update_variant(): void
    {
        $seller = $this->createSeller();
        $product = $this->createProduct($seller);

        $variant = $this->createVariant($product);

        Livewire::actingAs($seller)
            ->test(Edit::class, [
                'product' => $product,
                'variant' => $variant,
            ])
            ->set('sku', 'SKU-UPDATED')
            ->set('price', '79.99')
            ->set('compareAtPrice', '99.99')
            ->set('isActive', false)
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('show-success', message: 'Product variant updated successfully.')
            ->assertRedirectToRoute('seller.product.variants.index', ['product' => $product->id]);

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'product_id' => $product->id,
            'sku' => 'SKU-UPDATED',
            'price' => '79.99',
            'compare_at_price' => '99.99',
            'is_active' => false,
        ]);
    }

    public function test_seller_can_update_variant_without_compare_at_price(): void
    {
        $seller = $this->createSeller();
        $product = $this->createProduct($seller);

        $variant = $this->createVariant($product);

        Livewire::actingAs($seller)
            ->test(Edit::class, [
                'product' => $product,
                'variant' => $variant,
            ])
            ->set('sku', 'SKU-NO-COMPARE')
            ->set('price', '39.99')
            ->set('compareAtPrice', '')
            ->set('isActive', true)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirectToRoute('seller.product.variants.index', ['product' => $product->id]);

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'sku' => 'SKU-NO-COMPARE',
            'price' => '39.99',
            'compare_at_price' => null,
            'is_active' => true,
        ]);
    }

    public function test_seller_can_keep_existing_sku_when_updating(): void
    {
        $seller = $this->createSeller();
        $product = $this->createProduct($seller);

        $variant = $this->createVariant($product, [
            'sku' => 'SKU-KEEP',
        ]);

        Livewire::actingAs($seller)
            ->test(Edit::class, [
                'product' => $product,
                'variant' => $variant,
            ])
            ->set('sku', 'SKU-KEEP')
            ->set('price', '69.99')
            ->set('compareAtPrice', '79.99')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirectToRoute('seller.product.variants.index', ['product' => $product->id]);

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'sku' => 'SKU-KEEP',
            'price' => '69.99',
            'compare_at_price' => '79.99',
        ]);
    }

    public function test_seller_cannot_use_another_variants_sku(): void
    {
        $seller = $this->createSeller();
        $product = $this->createProduct($seller);

        $variant = $this->createVariant($product, [
            'sku' => 'SKU-001',
        ]);

        $otherVariant = $this->createVariant($product, [
            'sku' => 'SKU-002',
        ]);

        Livewire::actingAs($seller)
            ->test(Edit::class, [
                'product' => $product,
                'variant' => $variant,
            ])
            ->set('sku', $otherVariant->sku)
            ->set('price', '69.99')
            ->set('compareAtPrice', '79.99')
            ->call('save')
            ->assertHasErrors([
                'sku' => ['unique'],
            ]);

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'sku' => 'SKU-001',
            'price' => '49.99',
            'compare_at_price' => '59.99',
        ]);
    }

    public function test_compare_at_price_must_be_greater_than_or_equal_to_price(): void
    {
        $seller = $this->createSeller();
        $product = $this->createProduct($seller);

        $variant = $this->createVariant($product);

        Livewire::actingAs($seller)
            ->test(Edit::class, [
                'product' => $product,
                'variant' => $variant,
            ])
            ->set('price', '100.00')
            ->set('compareAtPrice', '90.00')
            ->call('save')
            ->assertHasErrors([
                'compareAtPrice' => ['gte:price'],
            ]);
    }

    public function test_sku_is_required(): void
    {
        $seller = $this->createSeller();
        $product = $this->createProduct($seller);

        $variant = $this->createVariant($product);

        Livewire::actingAs($seller)
            ->test(Edit::class, [
                'product' => $product,
                'variant' => $variant,
            ])
            ->set('sku', '')
            ->call('save')
            ->assertHasErrors([
                'sku' => ['required'],
            ]);
    }

    public function test_price_is_required(): void
    {
        $seller = $this->createSeller();
        $product = $this->createProduct($seller);

        $variant = $this->createVariant($product);

        Livewire::actingAs($seller)
            ->test(Edit::class, [
                'product' => $product,
                'variant' => $variant,
            ])
            ->set('price', '')
            ->call('save')
            ->assertHasErrors([
                'price' => ['required'],
            ]);
    }

    public function test_seller_cannot_edit_another_sellers_variant(): void
    {
        $seller = $this->createSeller();

        $otherSeller = $this->createSeller();
        $product = $this->createProduct($otherSeller);

        $variant = $this->createVariant($product);

        Livewire::actingAs($seller)
            ->test(Edit::class, [
                'product' => $product,
                'variant' => $variant,
            ])
            ->assertForbidden();
    }

    public function test_variant_must_belong_to_the_given_product(): void
    {
        $seller = $this->createSeller();

        $product = $this->createProduct($seller);
        $otherProduct = $this->createProduct($seller);

        $variant = $this->createVariant($otherProduct);

        Livewire::actingAs($seller)
            ->test(Edit::class, [
                'product' => $product,
                'variant' => $variant,
            ])
            ->assertStatus(404);
    }
}
