<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [
            // Users
            'view users',
            'view user',
            'create user',
            'update user',
            'delete user',

            // Stores
            'view stores',
            'view store',
            'create store',
            'update store',
            'delete store',
            'approve store',
            'reject store',
            'suspend store',

            // Products
            'view products',
            'view product',
            'create product',
            'update product',
            'delete product',

            // Inventory
            'view inventory',
            'adjust inventory',

            // Orders
            'view orders',
            'view order',
            'create order',
            'update order',
            'cancel order',

            // Payments
            'view payments',
            'view payment',
            'refund payment',

            // Deliveries
            'view deliveries',
            'view delivery',
            'assign delivery',
            'update delivery',

            // Categories
            'view categories',
            'create category',
            'update category',
            'delete category',

            // Reports
            'view reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $seller = Role::firstOrCreate([
            'name' => 'seller',
            'guard_name' => 'web',
        ]);

        $customer = Role::firstOrCreate([
            'name' => 'customer',
            'guard_name' => 'web',
        ]);

        $delivery = Role::firstOrCreate([
            'name' => 'delivery',
            'guard_name' => 'web',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Admin Permissions
        |--------------------------------------------------------------------------
        */

        $admin->syncPermissions(
            Permission::all()
        );

        /*
        |--------------------------------------------------------------------------
        | Seller Permissions
        |--------------------------------------------------------------------------
        */

        $seller->syncPermissions([
            'view stores',
            'view store',
            'create store',
            'update store',

            'view products',
            'view product',
            'create product',
            'update product',
            'delete product',

            'view inventory',
            'adjust inventory',

            'view orders',
            'view order',
            'update order',

            'view payments',
            'view payment',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Customer Permissions
        |--------------------------------------------------------------------------
        */

        $customer->syncPermissions([
            'view products',
            'view product',

            'view stores',
            'view store',

            'create order',
            'view orders',
            'view order',
            'cancel order',

            'view payment',
            'view deliveries',
            'view delivery',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Delivery Permissions
        |--------------------------------------------------------------------------
        */

        $delivery->syncPermissions([
            'view deliveries',
            'view delivery',
            'update delivery',

            'view order',
            'view orders',
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
