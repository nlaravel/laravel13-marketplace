<?php

declare(strict_types=1);

namespace Tests\Feature\Seller;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SellerAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cannot_access_seller_routes(): void
    {
        $customer = User::factory()->create();

        $response = $this->actingAs($customer)
            ->get(route('seller.dashboard'));

        $response->assertForbidden();
    }

    public function test_seller_can_access_seller_routes(): void
    {
        $seller = User::factory()->create();

        Role::findOrCreate('seller', 'web');

        $seller->assignRole('seller');

        $response = $this->actingAs($seller)
            ->get(route('seller.dashboard'));

        $response->assertOk();
    }
}
