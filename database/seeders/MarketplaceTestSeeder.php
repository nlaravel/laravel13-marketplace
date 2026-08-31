<?php

namespace Database\Seeders;

use App\Enums\CartStatus;
use App\Enums\CategoryStatus;
use App\Enums\ProductStatus;
use App\Enums\StoreStatus;
use App\Models\Cart;
use App\Models\Category;
use App\Models\CustomerProfile;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SellerProfile;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MarketplaceTestSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Admin
        |--------------------------------------------------------------------------
        */

        $admin = User::updateOrCreate(
            [
                'email' => 'admin@marketplace.test',
            ],
            [
                'name' => 'Test Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole('admin');

        /*
        |--------------------------------------------------------------------------
        | Seller
        |--------------------------------------------------------------------------
        */

        $seller = User::updateOrCreate(
            [
                'email' => 'seller@marketplace.test',
            ],
            [
                'name' => 'Test Seller',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $seller->assignRole('seller');

        $sellerProfile = SellerProfile::updateOrCreate(
            [
                'user_id' => $seller->id,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Store
        |--------------------------------------------------------------------------
        */

        $store = Store::updateOrCreate(
            [
                'slug' => 'test-store',
            ],
            [
                'seller_id' => $sellerProfile->id,
                'name' => 'Test Store',
                'description' => 'Development test store',
                'status' => StoreStatus::APPROVED,
                'approved_at' => now(),
                'approved_by' => $admin->id,
                'rejection_reason' => null,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Category
        |--------------------------------------------------------------------------
        */

        $category = Category::updateOrCreate(
            [
                'slug' => 'electronics',
            ],
            [
                'parent_id' => null,
                'name' => 'Electronics',
                'description' => 'Electronic products',
                'status' => CategoryStatus::ACTIVE,
                'sort_order' => 1,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Product
        |--------------------------------------------------------------------------
        */

        $product = Product::updateOrCreate(
            [
                'slug' => 'test-smartphone',
            ],
            [
                'store_id' => $store->id,
                'category_id' => $category->id,
                'name' => 'Test Smartphone',
                'description' => 'Development test product',
                'status' => ProductStatus::ACTIVE,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Product Variant
        |--------------------------------------------------------------------------
        */

        $variant = ProductVariant::updateOrCreate(
            [
                'sku' => 'TEST-SMARTPHONE-BLACK',
            ],
            [
                'product_id' => $product->id,
                'price' => 299.99,
                'compare_at_price' => 349.99,
                'is_active' => true,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Inventory
        |--------------------------------------------------------------------------
        */

        Inventory::updateOrCreate(
            [
                'product_variant_id' => $variant->id,
            ],
            [
                'quantity' => 100,
                'reserved_quantity' => 0,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Customer
        |--------------------------------------------------------------------------
        */

        $customer = User::updateOrCreate(
            [
                'email' => 'customer@marketplace.test',
            ],
            [
                'name' => 'Test Customer',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $customer->assignRole('customer');

        $customerProfile = CustomerProfile::updateOrCreate(
            [
                'user_id' => $customer->id,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Active Cart
        |--------------------------------------------------------------------------
        */

        $cart = Cart::firstOrCreate(
            [
                'user_id' => $customer->id,
                'status' => CartStatus::ACTIVE,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Output
        |--------------------------------------------------------------------------
        */

        $this->command->info('Marketplace test data created successfully.');

        $this->command->newLine();

        $this->command->info('Admin:');
        $this->command->info('  Email: admin@marketplace.test');
        $this->command->info('  Password: password');

        $this->command->newLine();

        $this->command->info('Seller:');
        $this->command->info('  Email: seller@marketplace.test');
        $this->command->info('  Password: password');

        $this->command->newLine();

        $this->command->info('Customer:');
        $this->command->info('  Email: customer@marketplace.test');
        $this->command->info('  Password: password');

        $this->command->newLine();

        $this->command->info('Store ID: ' . $store->id);
        $this->command->info('Product ID: ' . $product->id);
        $this->command->info('Variant ID: ' . $variant->id);
        $this->command->info('Inventory ID: ' . $variant->inventory->id);
        $this->command->info('Cart ID: ' . $cart->id);
    }
}
