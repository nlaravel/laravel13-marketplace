<?php

use Livewire\Component;
use App\Models\Order;
use App\Models\Address;
use App\Models\Cart;

new class extends Component
{
    public function getOrdersCount(): int
    {
        return Order::where('customer_id', auth()->id())->count();
    }

    public function getAddressesCount(): int
    {
        return Address::where('user_id', auth()->id())->count();
    }

    public function getCartItemsCount(): int
    {
        $cart = Cart::where('user_id', auth()->id())->first();

        return $cart?->items()->count() ?? 0;
    }

    public function getRecentOrders()
    {
        return Order::query()
            ->where('customer_id', auth()->id())
            ->latest()
            ->limit(5)
            ->get();
    }

    public function getDefaultAddress()
    {
        return Address::query()
            ->where('user_id', auth()->id())
            ->where('is_default', true)
            ->first();
    }
};
?>

<div class="space-y-6">

    {{-- Welcome --}}
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
        <div class="flex items-center justify-between gap-4">

            <div>
                <p class="text-sm font-medium text-gray-500">
                    Welcome back
                </p>

                <h2 class="mt-1 text-2xl font-bold text-gray-900">
                    {{ auth()->user()->name }}
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Here's what's happening with your account.
                </p>
            </div>

            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-gray-900 text-lg font-bold text-white">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

        </div>
    </div>


    {{-- Statistics --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

        {{-- Orders --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

            <p class="text-sm font-medium text-gray-500">
                My Orders
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-900">
                {{ $this->getOrdersCount() }}
            </p>

            <a href="#"
               class="mt-4 inline-block text-sm font-medium text-gray-700 hover:text-gray-900">
                View orders →
            </a>

        </div>


        {{-- Addresses --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

            <p class="text-sm font-medium text-gray-500">
                Addresses
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-900">
                {{ $this->getAddressesCount() }}
            </p>

            <a href="#"
               class="mt-4 inline-block text-sm font-medium text-gray-700 hover:text-gray-900">
                Manage addresses →
            </a>

        </div>


        {{-- Cart --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

            <p class="text-sm font-medium text-gray-500">
                Cart Items
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-900">
                {{ $this->getCartItemsCount() }}
            </p>

            <a href="#"
               class="mt-4 inline-block text-sm font-medium text-gray-700 hover:text-gray-900">
                View cart →
            </a>

        </div>

    </div>


    {{-- Content --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Recent Orders --}}
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 lg:col-span-2">

            <div class="border-b border-gray-200 px-6 py-4">

                <h3 class="font-semibold text-gray-900">
                    Recent Orders
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Your latest orders
                </p>

            </div>


            <div class="divide-y divide-gray-100">

                @forelse ($this->getRecentOrders() as $order)

                    <div class="flex items-center justify-between gap-4 px-6 py-4">

                        <div>
                            <p class="font-medium text-gray-900">
                                Order #{{ $order->id }}
                            </p>

                            <p class="mt-1 text-sm text-gray-500">
                                {{ $order->created_at?->format('M d, Y') }}
                            </p>
                        </div>


                        <div class="text-right">

                            <p class="font-semibold text-gray-900">
                                {{ number_format((float) $order->total, 2) }}
                                {{ $order->currency ?? 'USD' }}
                            </p>

                            <span class="mt-1 inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700">
                                {{ $order->status?->value ?? $order->status }}
                            </span>

                        </div>

                    </div>

                @empty

                    <div class="px-6 py-12 text-center">

                        <p class="font-medium text-gray-900">
                            No orders yet
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            Your recent orders will appear here.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>


        {{-- Default Address --}}
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">

            <div class="border-b border-gray-200 px-6 py-4">

                <h3 class="font-semibold text-gray-900">
                    Default Address
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Your primary delivery address
                </p>

            </div>


            <div class="p-6">

                @php
                    $defaultAddress = $this->getDefaultAddress();
                @endphp

                @if ($defaultAddress)

                    <div class="space-y-3">

                        <div>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $defaultAddress->label ?? 'Address' }}
                            </p>
                        </div>

                        <div class="text-sm leading-6 text-gray-600">

                            @if ($defaultAddress->address_line_1)
                                <p>
                                    {{ $defaultAddress->address_line_1 }}
                                </p>
                            @endif

                            @if ($defaultAddress->address_line_2)
                                <p>
                                    {{ $defaultAddress->address_line_2 }}
                                </p>
                            @endif

                            @if ($defaultAddress->city)
                                <p>
                                    {{ $defaultAddress->city }}
                                </p>
                            @endif

                            @if ($defaultAddress->state)
                                <p>
                                    {{ $defaultAddress->state }}
                                </p>
                            @endif

                            @if ($defaultAddress->postal_code)
                                <p>
                                    {{ $defaultAddress->postal_code }}
                                </p>
                            @endif

                        </div>

                        @if ($defaultAddress->phone)

                            <p class="text-sm text-gray-600">
                                {{ $defaultAddress->phone }}
                            </p>

                        @endif

                    </div>

                @else

                    <div class="py-6 text-center">

                        <p class="font-medium text-gray-900">
                            No default address
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            Add an address to make checkout faster.
                        </p>

                        <a href="#"
                           class="mt-4 inline-flex rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                            Add Address
                        </a>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- Quick Actions --}}
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

        <h3 class="font-semibold text-gray-900">
            Quick Actions
        </h3>

        <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">

            <a href="#"
               class="rounded-xl border border-gray-200 p-4 hover:bg-gray-50">

                <p class="font-medium text-gray-900">
                    Browse Products
                </p>

                <p class="mt-1 text-sm text-gray-500">
                    Find products in the marketplace
                </p>

            </a>


            <a href="#"
               class="rounded-xl border border-gray-200 p-4 hover:bg-gray-50">

                <p class="font-medium text-gray-900">
                    My Orders
                </p>

                <p class="mt-1 text-sm text-gray-500">
                    Track your orders
                </p>

            </a>


            <a href="#"
               class="rounded-xl border border-gray-200 p-4 hover:bg-gray-50">

                <p class="font-medium text-gray-900">
                    My Cart
                </p>

                <p class="mt-1 text-sm text-gray-500">
                    Review your shopping cart
                </p>

            </a>


            <a href="#"
               class="rounded-xl border border-gray-200 p-4 hover:bg-gray-50">

                <p class="font-medium text-gray-900">
                    Profile
                </p>

                <p class="mt-1 text-sm text-gray-500">
                    Manage your account
                </p>

            </a>

        </div>

    </div>

</div>
