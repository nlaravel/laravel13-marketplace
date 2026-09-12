
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>

                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                    Seller Portal
                </p>
            </div>

            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                Seller Dashboard
            </h1>

            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Overview of your stores and orders.
            </p>
        </div>

        <div class="flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 shadow-sm dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300">
            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
            Account active
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">

        {{-- Stores --}}
        <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900">
            <div class="absolute inset-x-0 top-0 h-1 bg-slate-400"></div>

            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Stores
                    </p>

                    <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900 dark:text-white">
                        {{ $this->storesCount }}
                    </p>

                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                        Your seller stores
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-slate-600 transition group-hover:bg-slate-900 group-hover:text-white dark:bg-slate-800 dark:text-slate-300 dark:group-hover:bg-white dark:group-hover:text-slate-900">
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
                                d="M3 10h18M5 10v10h14V10M7 10V6a5 5 0 0110 0v4"
                        />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Orders --}}
        <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900">
            <div class="absolute inset-x-0 top-0 h-1 bg-blue-500"></div>

            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Orders
                    </p>

                    <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900 dark:text-white">
                        {{ $this->ordersCount }}
                    </p>

                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                        Total seller orders
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition group-hover:bg-blue-600 group-hover:text-white dark:bg-blue-950/40 dark:text-blue-400 dark:group-hover:bg-blue-500">
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
                                d="M3 7h18M5 7v10h14V7M8 11h8"
                        />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Statuses --}}
        <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900">
            <div class="absolute inset-x-0 top-0 h-1 bg-violet-500"></div>

            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Active Statuses
                    </p>

                    <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900 dark:text-white">
                        {{ count($this->ordersByStatus) }}
                    </p>

                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                        Order statuses in use
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-50 text-violet-600 transition group-hover:bg-violet-600 group-hover:text-white dark:bg-violet-950/40 dark:text-violet-400 dark:group-hover:bg-violet-500">
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
                                d="M4 6h16M4 12h16M4 18h16"
                        />
                    </svg>
                </div>
            </div>
        </div>

    </div>

    {{-- Main Grid --}}
    <div class="grid gap-6 xl:grid-cols-3">

        {{-- Latest Orders --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-2 dark:border-slate-800 dark:bg-slate-900">

            {{-- Header --}}
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4 dark:border-slate-800">

                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="font-semibold text-slate-900 dark:text-white">
                            Latest Orders
                        </h2>

                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                            5
                        </span>
                    </div>

                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        Your most recent seller orders
                    </p>
                </div>

                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                    <svg
                            class="h-4 w-4"
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

            @if ($this->recentOrders->isEmpty())

                <div class="flex flex-col items-center justify-center px-5 py-16 text-center">

                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500">
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
                                    d="M3 7h18M5 7v10h14V7M8 11h8"
                            />
                        </svg>
                    </div>

                    <h3 class="mt-4 text-sm font-semibold text-slate-900 dark:text-white">
                        No orders yet
                    </h3>

                    <p class="mt-1 max-w-sm text-sm text-slate-500 dark:text-slate-400">
                        Your latest seller orders will appear here once customers place orders.
                    </p>

                </div>

            @else

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left">

                        <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/70 dark:border-slate-800 dark:bg-slate-950/40">

                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Order
                            </th>

                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Status
                            </th>

                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Total
                            </th>

                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Date
                            </th>

                        </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">

                        @foreach ($this->recentOrders as $order)

                            @php
                                $status = $order->status instanceof \BackedEnum
                                    ? $order->status->value
                                    : $order->status;

                                $statusLabel = str_replace('_', ' ', $status);

                                $statusConfig = match ($status) {
                                    'pending' => [
                                        'label' => 'Pending',
                                        'dot' => 'bg-amber-500',
                                        'badge' => 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-950/30 dark:text-amber-400',
                                    ],
                                    'confirmed' => [
                                        'label' => 'Confirmed',
                                        'dot' => 'bg-blue-500',
                                        'badge' => 'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-950/30 dark:text-blue-400',
                                    ],
                                    'processing' => [
                                        'label' => 'Processing',
                                        'dot' => 'bg-indigo-500',
                                        'badge' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20 dark:bg-indigo-950/30 dark:text-indigo-400',
                                    ],
                                    'shipped' => [
                                        'label' => 'Shipped',
                                        'dot' => 'bg-violet-500',
                                        'badge' => 'bg-violet-50 text-violet-700 ring-violet-600/20 dark:bg-violet-950/30 dark:text-violet-400',
                                    ],
                                    'delivered', 'completed' => [
                                        'label' => ucfirst($statusLabel),
                                        'dot' => 'bg-emerald-500',
                                        'badge' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-950/30 dark:text-emerald-400',
                                    ],
                                    'cancelled', 'canceled', 'rejected' => [
                                        'label' => ucfirst($statusLabel),
                                        'dot' => 'bg-red-500',
                                        'badge' => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-950/30 dark:text-red-400',
                                    ],
                                    'refunded' => [
                                        'label' => 'Refunded',
                                        'dot' => 'bg-orange-500',
                                        'badge' => 'bg-orange-50 text-orange-700 ring-orange-600/20 dark:bg-orange-950/30 dark:text-orange-400',
                                    ],
                                    default => [
                                        'label' => ucfirst($statusLabel),
                                        'dot' => 'bg-slate-400',
                                        'badge' => 'bg-slate-100 text-slate-700 ring-slate-600/20 dark:bg-slate-800 dark:text-slate-300',
                                    ],
                                };
                            @endphp

                            <tr class="group transition-colors hover:bg-slate-50/80 dark:hover:bg-slate-800/40">

                                {{-- Order --}}
                                <td class="whitespace-nowrap px-5 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                                            <svg
                                                    class="h-4 w-4"
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

                                        <div>
                                            <p class="text-sm font-semibold text-slate-900 dark:text-white">
                                                #{{ $order->id }}
                                            </p>

                                            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                                                Seller order
                                            </p>
                                        </div>

                                    </div>

                                </td>

                                {{-- Status --}}
                                <td class="whitespace-nowrap px-5 py-4">

                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $statusConfig['badge'] }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $statusConfig['dot'] }}"></span>

                                        {{ $statusConfig['label'] }}
                                    </span>

                                </td>

                                {{-- Total --}}
                                <td class="whitespace-nowrap px-5 py-4">

                                    <p class="text-sm font-semibold text-slate-900 dark:text-white">
                                        {{ number_format((float) $order->total_amount, 2) }}
                                    </p>

                                    <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                                        Order total
                                    </p>

                                </td>

                                {{-- Date --}}
                                <td class="whitespace-nowrap px-5 py-4 text-right">

                                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                        {{ $order->created_at?->format('M d, Y') }}
                                    </p>

                                    <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                                        {{ $order->created_at?->format('h:i A') }}
                                    </p>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>
                </div>

            @endif

        </section>

        {{-- Order Status Chart --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

            {{-- Header --}}
            <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">

                <div class="flex items-center justify-between">

                    <div>
                        <h2 class="font-semibold text-slate-900 dark:text-white">
                            Order Status
                        </h2>

                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            Current order distribution
                        </p>
                    </div>

                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                        <svg
                                class="h-4 w-4"
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

            @php
                $statusData = $this->ordersByStatus;
                $statusTotal = array_sum($statusData);
                $statusOffset = 0;

                $statusColors = [
                    'pending' => '#f59e0b',
                    'confirmed' => '#3b82f6',
                    'processing' => '#6366f1',
                    'shipped' => '#8b5cf6',
                    'delivered' => '#10b981',
                    'completed' => '#10b981',
                    'cancelled' => '#ef4444',
                    'canceled' => '#ef4444',
                    'rejected' => '#ef4444',
                    'refunded' => '#f97316',
                ];

                $statusDots = [
                    'pending' => 'bg-amber-500',
                    'confirmed' => 'bg-blue-500',
                    'processing' => 'bg-indigo-500',
                    'shipped' => 'bg-violet-500',
                    'delivered' => 'bg-emerald-500',
                    'completed' => 'bg-emerald-500',
                    'cancelled' => 'bg-red-500',
                    'canceled' => 'bg-red-500',
                    'rejected' => 'bg-red-500',
                    'refunded' => 'bg-orange-500',
                ];
            @endphp

            @if ($statusTotal > 0)

                {{-- Donut --}}
                <div class="flex justify-center px-5 pt-7">

                    <div class="relative h-48 w-48">

                        <svg
                                class="h-full w-full -rotate-90"
                                viewBox="0 0 100 100"
                                fill="none"
                        >
                            {{-- Background ring --}}
                            <circle
                                    cx="50"
                                    cy="50"
                                    r="40"
                                    pathLength="100"
                                    stroke="currentColor"
                                    stroke-width="12"
                                    class="text-slate-100 dark:text-slate-800"
                            />

                            @foreach ($statusData as $status => $total)

                                @php
                                    $percentage = ($total / $statusTotal) * 100;
                                    $color = $statusColors[$status] ?? '#64748b';
                                    $dashOffset = -$statusOffset;
                                    $statusOffset += $percentage;
                                @endphp

                                <circle
                                        cx="50"
                                        cy="50"
                                        r="40"
                                        pathLength="100"
                                        stroke="{{ $color }}"
                                        stroke-width="12"
                                        stroke-linecap="butt"
                                        stroke-dasharray="{{ $percentage }} {{ 100 - $percentage }}"
                                        stroke-dashoffset="{{ $dashOffset }}"
                                />

                            @endforeach

                        </svg>

                        {{-- Center --}}
                        <div class="absolute inset-0 flex flex-col items-center justify-center">

                            <span class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">
                                {{ $statusTotal }}
                            </span>

                            <span class="mt-0.5 text-xs font-medium text-slate-500 dark:text-slate-400">
                                Total Orders
                            </span>

                        </div>

                    </div>

                </div>

                {{-- Legend --}}
                <div class="space-y-1 px-5 pb-5 pt-6">

                    @foreach ($statusData as $status => $total)

                        @php
                            $percentage = ($total / $statusTotal) * 100;
                            $dotClass = $statusDots[$status] ?? 'bg-slate-500';
                        @endphp

                        <div class="flex items-center justify-between rounded-xl px-3 py-2.5 transition hover:bg-slate-50 dark:hover:bg-slate-800/60">

                            <div class="flex min-w-0 items-center gap-3">

                                <span class="h-2.5 w-2.5 shrink-0 rounded-full {{ $dotClass }}"></span>

                                <span class="truncate text-sm font-medium capitalize text-slate-700 dark:text-slate-300">
                                    {{ str_replace('_', ' ', $status) }}
                                </span>

                            </div>

                            <div class="ml-3 flex items-center gap-2">

                                <span class="text-xs text-slate-400">
                                    {{ number_format($percentage, 0) }}%
                                </span>

                                <span class="inline-flex min-w-7 items-center justify-center rounded-lg bg-slate-100 px-2 py-1 text-xs font-bold text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                    {{ $total }}
                                </span>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                {{-- Empty Chart --}}
                <div class="flex flex-col items-center justify-center px-5 py-16 text-center">

                    <div class="relative flex h-36 w-36 items-center justify-center">

                        <div class="absolute inset-0 rounded-full border-[14px] border-slate-100 dark:border-slate-800"></div>

                        <div class="relative text-center">
                            <p class="text-2xl font-bold text-slate-400 dark:text-slate-500">
                                0
                            </p>

                            <p class="text-xs text-slate-400 dark:text-slate-500">
                                Orders
                            </p>
                        </div>

                    </div>

                    <p class="mt-5 text-sm font-medium text-slate-700 dark:text-slate-300">
                        No order data
                    </p>

                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        Status information will appear here.
                    </p>

                </div>

            @endif

        </section>

    </div>

</div>

