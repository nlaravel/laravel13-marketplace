<?php

namespace App\Livewire\Customer;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(): View
    {
        $user = auth()->user();

        $cart = Cart::query()
            ->where('user_id', $user->id)
            ->with('items')
            ->first();

        return view('livewire.customer.dashboard', [
                'ordersCount' => Order::query()
                    ->where('customer_id', $user->id)
                    ->count(),

                'addressesCount' => Address::query()
                    ->where('user_id', $user->id)
                    ->count(),

                'cartItemsCount' => $cart?->items->sum('quantity') ?? 0,

            'recentOrders' => Order::query()
        ->where('customer_id', $user->id)
        ->latest()
        ->limit(5)
        ->get(),

            'defaultAddress' => Address::query()
        ->where('user_id', $user->id)
        ->where('is_default', true)
        ->first(),
        ]);
    }
}