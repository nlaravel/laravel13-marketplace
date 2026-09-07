<?php

declare(strict_types=1);

namespace Tests\Feature\Customer\Order;

use App\Enums\OrderStatus;
use App\Livewire\Customer\Orders\Index;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_view_orders_page(): void
    {
        $this->get(route('customer.orders.index'))
            ->assertRedirect();
    }

    public function test_customer_can_view_orders_page(): void
    {
        $customer = User::factory()->create();

        Livewire::actingAs($customer)
            ->test(Index::class)
            ->assertStatus(200)
            ->assertSee('My Orders');
    }

    public function test_customer_only_sees_their_own_orders(): void
    {
        $customer = User::factory()->create();

        $anotherCustomer = User::factory()->create();

        Order::factory()->create([
            'customer_id' => $customer->id,
            'order_number' => 'ORD-MINE-001',
            'status' => OrderStatus::PENDING,
            'total_amount' => 100,
        ]);

        Order::factory()->create([
            'customer_id' => $anotherCustomer->id,
            'order_number' => 'ORD-OTHER-001',
            'status' => OrderStatus::COMPLETED,
            'total_amount' => 200,
        ]);

        Livewire::actingAs($customer)
            ->test(Index::class)
            ->assertSee('ORD-MINE-001')
            ->assertDontSee('ORD-OTHER-001');
    }

    public function test_customer_can_see_view_order_link(): void
    {
        $customer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'order_number' => 'ORD-LINK-001',
        ]);

        Livewire::actingAs($customer)
            ->test(Index::class)
            ->assertSee(route('customer.orders.show', $order->id), false, );
    }
}
