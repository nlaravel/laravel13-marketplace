<div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                Customer Dashboard
            </p>

            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl">
                Welcome back, {{ auth()->user()->name }} 👋
            </h1>

            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                Here's a quick overview of your marketplace activity.
            </p>
        </div>

        <a
                href="{{ route('customer.orders.index') }}"
                wire:navigate
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100"
        >
            View Orders

            <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
            >
                <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5l7 7-7 7"
                />
            </svg>
        </a>
    </div>


    {{-- Statistics --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

        {{-- Orders --}}
        <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800">

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Total Orders
                    </p>

                    <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900 dark:text-white">
                        {{ $this->ordersCount }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        Orders placed
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                    <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                    >
                        <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M3 7h18M5 7l1 12h12l1-12M9 11v4M15 11v4M8 7l1-3h6l1 3"
                        />
                    </svg>
                </div>

            </div>

        </div>


        {{-- Addresses --}}
        <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800">

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Saved Addresses
                    </p>

                    <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900 dark:text-white">
                        {{ $this->addressesCount }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        Delivery addresses
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                    <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                    >
                        <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M12 21s7-5.4 7-11a7 7 0 10-14 0c0 5.6 7 11 7 11z"
                        />
                        <circle
                                cx="12"
                                cy="10"
                                r="2.5"
                                stroke-width="1.8"
                        />
                    </svg>
                </div>

            </div>

        </div>


        {{-- Cart --}}
        <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800">

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Cart Items
                    </p>

                    <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900 dark:text-white">
                        {{ $this->cartItemsCount }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        Items in your cart
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400">
                    <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                    >
                        <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M3 4h2l2.4 11.2a2 2 0 002 1.6h7.8a2 2 0 001.9-1.4L21 8H7"
                        />
                        <circle
                                cx="10"
                                cy="20"
                                r="1"
                                stroke-width="1.8"
                        />
                        <circle
                                cx="18"
                                cy="20"
                                r="1"
                                stroke-width="1.8"
                        />
                    </svg>
                </div>

            </div>

        </div>

    </div>
    {{-- Orders Activity --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">

        <div class="border-b border-slate-200 px-6 py-5 dark:border-slate-700">
            <div>
                <h2 class="font-semibold text-slate-900 dark:text-white">
                    Orders Activity
                </h2>

                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                    Your orders over time
                </p>
            </div>
        </div>

        <div class="p-6">
            <div class="relative h-72">
                <canvas id="ordersActivityChart"></canvas>
            </div>
        </div>

    </div>

    {{-- Main Content --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Recent Orders --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800 lg:col-span-2">

            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5 dark:border-slate-700">

                <div>
                    <h2 class="font-semibold text-slate-900 dark:text-white">
                        Recent Orders
                    </h2>

                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        Your latest marketplace activity
                    </p>
                </div>

                @if ($this->ordersCount > 0)
                    <a
                            href="{{ route('customer.orders.index') }}"
                            wire:navigate
                            class="text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300"
                    >
                        View all
                    </a>
                @endif

            </div>


            <div class="divide-y divide-slate-100 dark:divide-slate-700">

                @forelse($this->recentOrders as $order)

                    <a
                            href="{{ route('customer.orders.show', $order->id) }}"
                            wire:navigate
                            class="block px-6 py-5 transition hover:bg-slate-50 dark:hover:bg-slate-700/30"
                    >

                        <div class="flex items-center justify-between gap-4">

                            <div class="min-w-0">

                                <div class="flex items-center gap-3">

                                    <p class="truncate font-semibold text-slate-900 dark:text-white">
                                        #{{ $order->order_number }}
                                    </p>

                                    @php
                                        $status = $order->status->value ?? $order->status;

                                        $statusClasses = match ($status) {
                                            'pending' => 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-500/10 dark:text-amber-400',
                                            'processing' => 'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-500/10 dark:text-blue-400',
                                            'completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-500/10 dark:text-emerald-400',
                                            'cancelled' => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-500/10 dark:text-red-400',
                                            'delivered' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20 dark:bg-indigo-500/10 dark:text-indigo-400',
                                            default => 'bg-slate-50 text-slate-700 ring-slate-600/20 dark:bg-slate-700 dark:text-slate-300',
                                        };
                                    @endphp

                                    <span
                                            class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ $statusClasses }}"
                                    >
                                        {{ ucfirst($status) }}
                                    </span>

                                </div>

                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                    {{ $order->created_at->format('M d, Y') }}
                                </p>

                            </div>


                            <div class="flex shrink-0 items-center gap-4">

                                <div class="text-right">

                                    <p class="font-semibold text-slate-900 dark:text-white">
                                        {{ number_format($order->total_amount, 2) }}
                                    </p>

                                    <p class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $order->currency ?? 'USD' }}
                                    </p>

                                </div>

                                <svg
                                        class="h-5 w-5 text-slate-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                >
                                    <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 5l7 7-7 7"
                                    />
                                </svg>

                            </div>

                        </div>

                    </a>

                @empty

                    <div class="px-6 py-14 text-center">

                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400 dark:bg-slate-700 dark:text-slate-500">

                            <svg
                                    class="h-7 w-7"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                            >
                                <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M3 7h18M5 7l1 12h12l1-12M9 11v4M15 11v4M8 7l1-3h6l1 3"
                                />
                            </svg>

                        </div>

                        <p class="mt-4 font-medium text-slate-900 dark:text-white">
                            No orders yet
                        </p>

                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Your orders will appear here once you complete your first purchase.
                        </p>

                        <button
                                type="button"
                                class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white opacity-60 cursor-not-allowed"
                        >
                            Start Shopping
                        </button>

                    </div>

                @endforelse

            </div>

        </div>


        {{-- Default Address --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">

            <div class="border-b border-slate-200 px-6 py-5 dark:border-slate-700">

                <div class="flex items-center justify-between">

                    <div>
                        <h2 class="font-semibold text-slate-900 dark:text-white">
                            Default Address
                        </h2>

                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            Your preferred delivery address
                        </p>
                    </div>

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">

                        <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                        >
                            <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M12 21s7-5.4 7-11a7 7 0 10-14 0c0 5.6 7 11 7 11z"
                            />
                            <circle
                                    cx="12"
                                    cy="10"
                                    r="2.5"
                                    stroke-width="1.8"
                            />
                        </svg>

                    </div>

                </div>

            </div>


            <div class="p-6">

                @if($this->defaultAddress)

                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-700/40">

                        <div class="flex items-start gap-3">

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white text-indigo-600 shadow-sm dark:bg-slate-800 dark:text-indigo-400">
                                <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                >
                                    <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.8"
                                            d="M12 21s7-5.4 7-11a7 7 0 10-14 0c0 5.6 7 11 7 11z"
                                    />
                                    <circle
                                            cx="12"
                                            cy="10"
                                            r="2.5"
                                            stroke-width="1.8"
                                    />
                                </svg>
                            </div>

                            <div class="min-w-0">

                                <p class="font-semibold text-slate-900 dark:text-white">
                                    {{ $this->defaultAddress->label ?? 'Address' }}
                                </p>

                                <p class="mt-1 text-sm leading-6 text-slate-500 dark:text-slate-400">
                                    {{ $this->defaultAddress->address_line }}
                                </p>

                            </div>

                        </div>

                    </div>

                    <a
                            href="{{ route('customer.addresses.index') }}"
                            wire:navigate
                            class="mt-5 inline-flex items-center gap-2 text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300"
                    >
                        Manage addresses

                        <svg
                                class="h-4 w-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                        >
                            <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </a>

                @else

                    <div class="py-6 text-center">

                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400 dark:bg-slate-700 dark:text-slate-500">

                            <svg
                                    class="h-6 w-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                            >
                                <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M12 21s7-5.4 7-11a7 7 0 10-14 0c0 5.6 7 11 7 11z"
                                />
                                <circle
                                        cx="12"
                                        cy="10"
                                        r="2.5"
                                        stroke-width="1.8"
                                />
                            </svg>

                        </div>

                        <p class="mt-4 font-medium text-slate-900 dark:text-white">
                            No default address
                        </p>

                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Add an address to make checkout faster.
                        </p>

                        <a
                                href="{{ route('customer.addresses.index') }}"
                                wire:navigate
                                class="mt-5 inline-flex items-center rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100"
                        >
                            Add Address
                        </a>

                    </div>

                @endif

            </div>

        </div>

    </div>

    @assets
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endassets

    @script
    <script>
        const ordersByMonth = @js($this->ordersByMonth);

        const labels = Object.keys(ordersByMonth);
        const data = Object.values(ordersByMonth);

        const canvas = $wire.$el.querySelector('#ordersActivityChart');

        if (canvas) {
            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Orders',
                        data: data,
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    plugins: {
                        legend: {
                            display: false,
                        },
                    },

                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                            },
                        },

                        x: {
                            grid: {
                                display: false,
                            },
                        },
                    },
                },
            });
        }
    </script>
    @endscript
</div>