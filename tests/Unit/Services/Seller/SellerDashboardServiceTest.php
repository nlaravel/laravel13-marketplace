<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Seller;

use App\Enums\SellerOrderStatus;
use App\Models\SellerOrder;
use App\Models\SellerProfile;
use App\Models\Store;
use App\Models\User;
use App\Services\Seller\SellerDashboardService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerDashboardServiceTest extends TestCase
{
    use RefreshDatabase;

    private SellerDashboardService $dashboardService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dashboardService = app(SellerDashboardService::class);
    }

    public function test_stores_count_returns_only_sellers_stores(): void
    {
        $seller = User::factory()->create();
        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        Store::factory()->count(3)->create([
            'seller_id' => $sellerProfile->id,
        ]);

        Store::factory()->create();

        $this->assertSame(3, $this->dashboardService->storesCount($seller->id));
    }

    public function test_orders_count_returns_only_orders_from_sellers_stores(): void
    {
        $seller = User::factory()->create();
        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        SellerOrder::factory()->count(3)->create([
            'store_id' => $store->id,
        ]);

        SellerOrder::factory()->create();

        $this->assertSame(3, $this->dashboardService->ordersCount($seller->id));
    }

    public function test_recent_orders_returns_latest_five_orders_for_seller(): void
    {
        $seller = User::factory()->create();
        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        $orders = SellerOrder::factory()
            ->count(7)
            ->create([
                'store_id' => $store->id,
            ]);

        SellerOrder::factory()->create();

        $recentOrders = $this->dashboardService->recentOrders($seller->id);

        $this->assertCount(5, $recentOrders);

        $expectedIds = $orders
            ->sortByDesc('created_at')
            ->take(5)
            ->pluck('id')
            ->values()
            ->all();

        $actualIds = $recentOrders
            ->pluck('id')
            ->values()
            ->all();

        $this->assertSame($expectedIds, $actualIds);
    }

    public function test_orders_by_status_returns_status_counts_for_seller(): void
    {
        $seller = User::factory()->create();
        $sellerProfile = SellerProfile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $store = Store::factory()->create([
            'seller_id' => $sellerProfile->id,
        ]);

        SellerOrder::factory()->count(3)->create([
            'store_id' => $store->id,
            'status' => SellerOrderStatus::PENDING,
        ]);

        SellerOrder::factory()->count(2)->create([
            'store_id' => $store->id,
            'status' => SellerOrderStatus::PROCESSING,
        ]);

        SellerOrder::factory()->create([
            'store_id' => $store->id,
            'status' => SellerOrderStatus::SHIPPED,
        ]);

        $result = $this->dashboardService->ordersByStatus($seller->id);

        $this->assertSame(3, $result[SellerOrderStatus::PENDING->value]);
        $this->assertSame(2, $result[SellerOrderStatus::PROCESSING->value]);
        $this->assertSame(1, $result[SellerOrderStatus::SHIPPED->value]);
    }

    public function test_seller_cannot_see_another_sellers_stores_or_orders(): void
    {
        $sellerA = User::factory()->create();
        $sellerAProfile = SellerProfile::factory()->create([
            'user_id' => $sellerA->id,
        ]);

        $storeA = Store::factory()->create([
            'seller_id' => $sellerAProfile->id,
        ]);

        SellerOrder::factory()->count(2)->create([
            'store_id' => $storeA->id,
        ]);

        $sellerB = User::factory()->create();
        $sellerBProfile = SellerProfile::factory()->create([
            'user_id' => $sellerB->id,
        ]);

        $storeB = Store::factory()->create([
            'seller_id' => $sellerBProfile->id,
        ]);

        SellerOrder::factory()->count(5)->create([
            'store_id' => $storeB->id,
        ]);

        $this->assertSame(2, $this->dashboardService->ordersCount($sellerA->id));

        $this->assertSame(5, $this->dashboardService->ordersCount($sellerB->id));

        $this->assertSame(1, $this->dashboardService->storesCount($sellerA->id));

        $this->assertSame(1, $this->dashboardService->storesCount($sellerB->id));
    }
}
