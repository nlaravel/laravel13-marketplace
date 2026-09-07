<?php

declare(strict_types=1);

namespace Tests\Feature\Customer\Order;

use App\Enums\OrderStatus;
use App\Exceptions\OrderException;
use App\Livewire\Customer\Orders\Show;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_view_order_details(): void
    {
        $order = Order::factory()->create();

        $this->get(route('customer.orders.show', $order->id))
            ->assertRedirect();
    }

    public function test_customer_can_view_own_order_details(): void
    {
        $customer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'order_number' => 'ORD-SHOW-001',
            'status' => OrderStatus::PENDING,
        ]);

        Livewire::actingAs($customer)
            ->test(Show::class, [
                'order' => $order->id,
            ])
            ->assertStatus(200)
            ->assertSee('ORD-SHOW-001');
    }

    public function test_customer_cannot_view_another_users_order(): void
    {
        $customer = User::factory()->create();

        $anotherCustomer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $anotherCustomer->id,
            'order_number' => 'ORD-OTHER-001',
        ]);

        $this->expectException(ModelNotFoundException::class);

        Livewire::actingAs($customer)
            ->test(Show::class, [
                'order' => $order->id,
            ]);
    }

    public function test_customer_can_cancel_own_order(): void
    {
        $customer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::PENDING,
        ]);

        Livewire::actingAs($customer)
            ->test(Show::class, [
                'order' => $order->id,
            ])
            ->call('cancel')
            ->assertDispatched('show-success', message: 'Order cancelled successfully.', );

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::CANCELLED,
        ]);
    }

    public function test_customer_cannot_cancel_processing_order(): void
    {
        $customer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::PROCESSING,
        ]);

        $this->expectException(OrderException::class);

        Livewire::actingAs($customer)
            ->test(Show::class, [
                'order' => $order->id,
            ])
            ->call('cancel');
    }

    public function test_customer_cannot_cancel_delivered_order(): void
    {
        $customer = User::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::DELIVERED,
        ]);

        $this->expectException(OrderException::class);

        Livewire::actingAs($customer)
            ->test(Show::class, [
                'order' => $order->id,
            ])
            ->call('cancel');
    }
}
