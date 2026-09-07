<?php

declare(strict_types=1);

namespace Tests\Feature\Customer;

use App\Enums\OrderStatus;
use App\Livewire\Customer\Dashboard;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use App\Services\Customer\CustomerDashboardService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_view_dashboard(): void
    {
        $this->get(route('customer.dashboard'))
            ->assertRedirect();
    }

    public function test_customer_can_view_dashboard(): void
    {
        $customer = User::factory()->create();

        Livewire::actingAs($customer)
            ->test(Dashboard::class)
            ->assertStatus(200)
            ->assertSee('Welcome back')
            ->assertSee('Orders')
            ->assertSee('Addresses')
            ->assertSee('Cart Items')
            ->assertSee('Recent Orders')
            ->assertSee('Default Address');
    }

    public function test_dashboard_shows_customer_statistics(): void
    {
        $customer = User::factory()->create();

        Order::factory()
            ->count(2)
            ->create([
                'customer_id' => $customer->id,
            ]);

        Address::factory()
            ->count(3)
            ->create([
                'user_id' => $customer->id,
            ]);

        Cart::factory()->create([
            'user_id' => $customer->id,
        ]);

        Livewire::actingAs($customer)
            ->test(Dashboard::class)
            ->assertSee('2')
            ->assertSee('3');
    }

    public function test_dashboard_only_shows_customer_orders(): void
    {
        $customer = User::factory()->create();
        $anotherCustomer = User::factory()->create();

        Order::factory()->create([
            'customer_id' => $customer->id,
            'order_number' => 'ORD-DASH-MINE-001',
        ]);

        Order::factory()->create([
            'customer_id' => $anotherCustomer->id,
            'order_number' => 'ORD-DASH-OTHER-001',
        ]);

        Livewire::actingAs($customer)
            ->test(Dashboard::class)
            ->assertSee('ORD-DASH-MINE-001')
            ->assertDontSee('ORD-DASH-OTHER-001');
    }

    public function test_dashboard_shows_recent_orders(): void
    {
        $customer = User::factory()->create();

        Order::factory()->create([
            'customer_id' => $customer->id,
            'order_number' => 'ORD-DASH-RECENT-001',
            'status' => OrderStatus::PENDING,
        ]);

        Livewire::actingAs($customer)
            ->test(Dashboard::class)
            ->assertSee('ORD-DASH-RECENT-001');
    }

    public function test_dashboard_shows_empty_state_when_customer_has_no_orders(): void
    {
        $customer = User::factory()->create();

        Livewire::actingAs($customer)
            ->test(Dashboard::class)
            ->assertSee('No orders yet')
            ->assertSee('Your orders will appear here once you complete your first purchase.');
    }

    public function test_dashboard_shows_default_address(): void
    {
        $customer = User::factory()->create();

        Address::factory()->create([
            'user_id' => $customer->id,
            'label' => 'Home',
            'address_line' => '123 Main Street',
            'is_default' => true,
        ]);

        Livewire::actingAs($customer)
            ->test(Dashboard::class)
            ->assertSee('Home')
            ->assertSee('123 Main Street');
    }

    public function test_dashboard_shows_default_address_empty_state(): void
    {
        $customer = User::factory()->create();

        Livewire::actingAs($customer)
            ->test(Dashboard::class)
            ->assertSee('No default address')
            ->assertSee('Add an address to make checkout faster.');
    }

    public function test_dashboard_can_get_orders_by_status(): void
    {
        $customer = User::factory()->create();

        Order::factory()
            ->count(2)
            ->create([
                'customer_id' => $customer->id,
                'status' => OrderStatus::PENDING,
            ]);

        Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::COMPLETED,
        ]);

        Order::factory()->create([
            'customer_id' => User::factory()->create()->id,
            'status' => OrderStatus::PENDING,
        ]);

        $service = app(CustomerDashboardService::class);

        $result = $service->ordersByStatus($customer->id);

        $this->assertSame(2, $result[OrderStatus::PENDING->value]);
        $this->assertSame(1, $result[OrderStatus::COMPLETED->value]);
    }

    public function test_dashboard_can_get_orders_by_month(): void
    {
        $customer = User::factory()->create();

        Order::factory()->create([
            'customer_id' => $customer->id,
            'created_at' => '2026-01-15 10:00:00',
        ]);

        Order::factory()->create([
            'customer_id' => $customer->id,
            'created_at' => '2026-01-15 12:00:00',
        ]);

        Order::factory()->create([
            'customer_id' => $customer->id,
            'created_at' => '2026-02-10 10:00:00',
        ]);

        Order::factory()->create([
            'customer_id' => User::factory()->create()->id,
            'created_at' => '2026-01-20 10:00:00',
        ]);

        $service = app(CustomerDashboardService::class);

        $result = $service->ordersByMonth($customer->id);

        $this->assertSame([
            '2026-01' => 2,
            '2026-02' => 1,
        ], $result);
    }
}
