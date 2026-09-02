<?php

namespace App\Livewire\Customer;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Dashboard extends Component
{
    #[Computed]
    public function ordersCount(): int
    {
        return Order::query()
            ->where('customer_id', auth()->id())
            ->count();
    }

    #[Computed]
    public function addressesCount(): int
    {
        return Address::query()
            ->where('user_id', auth()->id())
            ->count();
    }

    #[Computed]
    public function cartItemsCount(): int
    {
        return Cart::query()
            ->where('user_id', auth()->id())
            ->with('items')
            ->first()?->items
            ->sum('quantity') ?? 0;
    }

    #[Computed]
    public function recentOrders()
    {
        return Order::query()
            ->where('customer_id', auth()->id())
            ->latest()
            ->limit(5)
            ->get();
    }

    #[Computed]
    public function defaultAddress(): ?Address
    {
        return Address::query()
            ->where('user_id', auth()->id())
            ->where('is_default', true)
            ->first();
    }

    public function render(): View
    {
        return view('livewire.customer.dashboard');
    }
}