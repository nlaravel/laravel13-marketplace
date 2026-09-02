<x-customer-layout title="Dashboard">

    <div class="space-y-6">

        {{-- Welcome --}}
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                Welcome back, {{ auth()->user()->name }} 👋
            </h1>

            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Here's what's happening with your marketplace account.
            </p>
        </div>

        {{-- Statistics --}}
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

            {{-- Orders --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Orders
                </p>

                <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">
                    {{ $this->ordersCount }}
                </p>
            </div>

            {{-- Addresses --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Addresses
                </p>

                <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">
                    {{ $this->addressesCount }}
                </p>
            </div>

            {{-- Cart --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Cart Items
                </p>

                <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">
                    {{ $this->cartItemsCount }}
                </p>
            </div>

        </div>

        {{-- Recent Orders --}}
        <div class="rounded-xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800">

            <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-700">
                <h2 class="font-semibold text-slate-900 dark:text-white">
                    Recent Orders
                </h2>
            </div>

            <div class="divide-y divide-slate-200 dark:divide-slate-700">

                @forelse($this->recentOrders as $order)

                    <div class="flex items-center justify-between px-6 py-4">

                        <div>
                            <p class="font-medium text-slate-900 dark:text-white">
                                Order #{{ $order->id }}
                            </p>

                            <p class="text-sm text-slate-500 dark:text-slate-400">
                                {{ $order->created_at->format('M d, Y') }}
                            </p>
                        </div>

                        <div class="text-right">

                            <p class="font-medium text-slate-900 dark:text-white">
                                {{ number_format($order->total, 2) }}
                            </p>

                            <span class="text-sm text-slate-500 dark:text-slate-400">
                                {{ $order->status->value ?? $order->status }}
                            </span>

                        </div>

                    </div>

                @empty

                    <div class="px-6 py-10 text-center">

                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            You don't have any orders yet.
                        </p>

                    </div>

                @endforelse

            </div>
        </div>

        {{-- Default Address --}}
        <div class="rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">

            <h2 class="font-semibold text-slate-900 dark:text-white">
                Default Address
            </h2>

            @if($this->defaultAddress)

                <div class="mt-4">
                    <p class="font-medium text-slate-900 dark:text-white">
                        {{ $this->defaultAddress->label ?? 'Address' }}
                    </p>

                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        {{ $this->defaultAddress->address_line_1 }}
                    </p>
                </div>

            @else

                <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">
                    You haven't added a default address yet.
                </p>

            @endif

        </div>

    </div>

</x-customer-layout>