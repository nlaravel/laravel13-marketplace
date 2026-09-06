<?php

declare(strict_types=1);

namespace App\Livewire\Customer\Orders;

use App\Services\Orders\OrderService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.customer-layout')]
class Index extends Component
{
    use WithPagination;

private OrderService $orderService;

    public function boot(OrderService $orderService): void
    {
        $this->orderService = $orderService;
    }

    #[Computed]
    public function orders(): LengthAwarePaginator
    {
        return $this->orderService->getCustomerOrders(
            auth()->user(),
        );
    }

    public function render(): View
    {
        return view('livewire.customer.orders.index');
    }
}