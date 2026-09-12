
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
            Seller Dashboard
        </h1>

        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            Overview of your stores and orders.
        </p>
    </div>

    {{-- Stats --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">

        {{-- Stores --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Stores
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">
                        {{ $this->storesCount }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
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
                                d="M3 10h18M5 10v10h14V10M7 10V6a5 5 0 0110 0v4"
                        />
                    </svg>
                </div>

            </div>
        </div>

        {{-- Orders --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Orders
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">
                        {{ $this->ordersCount }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
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
                                d="M3 7h18M5 7v10h14V7M8 11h8"
                        />
                    </svg>
                </div>

            </div>
        </div>

        {{-- Order Statuses --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Order Statuses
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">
                        {{ count($this->ordersByStatus) }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
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
                                d="M4 6h16M4 12h16M4 18h16"
                        />
                    </svg>
                </div>

            </div>
        </div>

    </div>

    {{-- Main Grid --}}
    <div class="grid gap-6 xl:grid-cols-3">

        {{-- Recent Orders --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-2 dark:border-slate-800 dark:bg-slate-900">

            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4 dark:border-slate-800">

                <div>
                    <h2 class="font-semibold text-slate-900 dark:text-white">
                        Recent Orders
                    </h2>

                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        Your latest seller orders
                    </p>
                </div>

            </div>

            @if ($this->recentOrders->isEmpty())

                <div class="px-5 py-12 text-center">
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        No orders yet.
                    </p>
                </div>

            @else

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">

                        <thead class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-950">
                        <tr>
                            <th class="px-5 py-3 font-medium text-slate-500 dark:text-slate-400">
                                Order
                            </th>

                            <th class="px-5 py-3 font-medium text-slate-500 dark:text-slate-400">
                                Status
                            </th>

                            <th class="px-5 py-3 font-medium text-slate-500 dark:text-slate-400">
                                Total
                            </th>

                            <th class="px-5 py-3 font-medium text-slate-500 dark:text-slate-400">
                                Date
                            </th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">

                        @foreach ($this->recentOrders as $order)

                            <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/50">

                                <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">
                                    #{{ $order->id }}
                                </td>

                                <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                            {{ $order->status instanceof \BackedEnum
                                                ? $order->status->value
                                                : $order->status }}
                                        </span>
                                </td>

                                <td class="px-5 py-4 text-slate-700 dark:text-slate-300">
                                    {{ number_format((float) $order->total_amount, 2) }}
                                </td>

                                <td class="px-5 py-4 text-slate-500 dark:text-slate-400">
                                    {{ $order->created_at?->format('M d, Y') }}
                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>
                </div>

            @endif

        </div>

        {{-- Order Status Summary --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

            <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">

                <h2 class="font-semibold text-slate-900 dark:text-white">
                    Order Status
                </h2>

                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                    Current order distribution
                </p>

            </div>

            <div class="space-y-4 p-5">

                @forelse ($this->ordersByStatus as $status => $total)

                    <div class="flex items-center justify-between">

                        <span class="text-sm font-medium capitalize text-slate-600 dark:text-slate-300">
                            {{ str_replace('_', ' ', $status) }}
                        </span>

                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                            {{ $total }}
                        </span>

                    </div>

                @empty

                    <p class="py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                        No order data available.
                    </p>

                @endforelse

            </div>

        </div>

    </div>

</div>
