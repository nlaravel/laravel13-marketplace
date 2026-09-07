<?php

declare(strict_types=1);

namespace App\Livewire\Customer\Orders;

use App\Models\Order;
use App\Services\Orders\OrderService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.customer-layout')]
class Show extends Component
{
    private OrderService $orderService;

    public int $orderId;

    public function boot(OrderService $orderService): void
    {
        $this->orderService = $orderService;
    }

    public function mount(int $order): void
    {
        $this->orderId = $order;

        if (session()->has('checkout_success')) {
            $this->dispatch('show-success', message: session()->pull('checkout_success'), );
        }
    }

    #[Computed]
    public function order(): Order
    {
        return $this->orderService->getCustomerOrder(auth()->user(), $this->orderId);
    }

    public function cancel(): void
    {
        $this->orderService->cancelCustomerOrder(auth()->user(), $this->orderId, );

        $this->dispatch('show-success', message: 'Order cancelled successfully.', );
    }

    public function render(): View
    {
        return view('livewire.customer.orders.show');
    }
}
