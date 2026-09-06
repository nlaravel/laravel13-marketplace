<div class="space-y-6">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-center gap-3">

            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400">
                <svg class="h-6 w-6"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.7"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"/>
                </svg>
            </div>

            <div>
                <h2 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">
                    My Orders
                </h2>

                <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">
                    View and manage your recent orders.
                </p>
            </div>

        </div>

        @if($this->orders->total() > 0)
            <div class="inline-flex w-fit items-center rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                {{ $this->orders->total() }}
                {{ $this->orders->total() === 1 ? 'order' : 'orders' }}
            </div>
        @endif

    </div>


    {{-- =========================================================
        ORDERS
    ========================================================== --}}
    @if($this->orders->isNotEmpty())

        <div class="space-y-4">

            @foreach($this->orders as $order)

                @php
                    $status = $order->status->value;

                    $statusClasses = match ($status) {
                        'pending' => 'bg-amber-50 text-amber-700 ring-amber-600/10 dark:bg-amber-950/30 dark:text-amber-400 dark:ring-amber-400/20',
                        'confirmed' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/10 dark:bg-indigo-950/30 dark:text-indigo-400 dark:ring-indigo-400/20',
                        'processing' => 'bg-blue-50 text-blue-700 ring-blue-600/10 dark:bg-blue-950/30 dark:text-blue-400 dark:ring-blue-400/20',
                        'partially_shipped', 'shipped' => 'bg-violet-50 text-violet-700 ring-violet-600/10 dark:bg-violet-950/30 dark:text-violet-400 dark:ring-violet-400/20',
                        'partially_delivered', 'delivered', 'completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/10 dark:bg-emerald-950/30 dark:text-emerald-400 dark:ring-emerald-400/20',
                        'cancelled' => 'bg-rose-50 text-rose-700 ring-rose-600/10 dark:bg-rose-950/30 dark:text-rose-400 dark:ring-rose-400/20',
                        default => 'bg-slate-100 text-slate-600 ring-slate-500/10 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-400/20',
                    };

                    $statusLabel = str($status)
                        ->replace('_', ' ')
                        ->title();

                    $itemCount = $order->items->sum('quantity');
                @endphp


                <article
                        wire:key="order-{{ $order->id }}"
                        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-200 hover:shadow-md dark:border-slate-700 dark:bg-slate-900"
                >

                    {{-- =================================================
                        ORDER HEADER
                    ================================================== --}}
                    <div class="border-b border-slate-100 px-5 py-5 dark:border-slate-800">

                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                            <div class="flex min-w-0 items-center gap-3">

                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                                    <svg class="h-5 w-5"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="1.7"
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>

                                <div class="min-w-0">

                                    <div class="flex flex-wrap items-center gap-2">

                                        <h3 class="truncate text-sm font-bold text-slate-900 dark:text-white">
                                            {{ $order->order_number }}
                                        </h3>

                                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide ring-1 ring-inset {{ $statusClasses }}">
                                            {{ $statusLabel }}
                                        </span>

                                    </div>

                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                        {{ $order->created_at?->format('M d, Y · h:i A') }}
                                    </p>

                                </div>

                            </div>


                            {{-- Total --}}
                            <div class="sm:text-right">

                                <p class="text-[11px] font-medium uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                    Order Total
                                </p>

                                <p class="mt-0.5 text-lg font-bold tracking-tight text-slate-900 dark:text-white">
                                    {{ $order->currency }}
                                    {{ number_format((float) $order->total_amount, 2) }}
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                        ORDER BODY
                    ================================================== --}}
                    <div class="p-5">

                        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_280px]">


                            {{-- =================================================
                                ITEMS
                            ================================================== --}}
                            <div>

                                <div class="mb-3 flex items-center justify-between">

                                    <div>
                                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">
                                            Order Items
                                        </h4>

                                        <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                                            {{ $itemCount }}
                                            {{ $itemCount === 1 ? 'item' : 'items' }}
                                        </p>
                                    </div>

                                </div>


                                <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700">

                                    <div class="divide-y divide-slate-100 dark:divide-slate-800">

                                        @foreach($order->items as $item)

                                            <div class="flex items-center gap-3 px-4 py-3.5 transition hover:bg-slate-50 dark:hover:bg-slate-800/60">

                                                {{-- Product placeholder --}}
                                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500">

                                                    <svg class="h-5 w-5"
                                                         fill="none"
                                                         stroke="currentColor"
                                                         viewBox="0 0 24 24">
                                                        <path stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              stroke-width="1.5"
                                                              d="M4 7h16v13H4zM8 7V5a4 4 0 018 0v2M8 12h8"/>
                                                    </svg>

                                                </div>


                                                {{-- Item info --}}
                                                <div class="min-w-0 flex-1">

                                                    <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">
                                                        Product Variant #{{ $item->product_variant_id }}
                                                    </p>

                                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                                        Qty: {{ $item->quantity }}
                                                        <span class="mx-1 text-slate-300 dark:text-slate-600">·</span>
                                                        {{ $order->currency }}
                                                        {{ number_format((float) $item->unit_price, 2) }} each
                                                    </p>

                                                </div>


                                                {{-- Item total --}}
                                                <div class="shrink-0 text-right">

                                                    <p class="text-sm font-bold text-slate-900 dark:text-white">
                                                        {{ $order->currency }}
                                                        {{ number_format((float) $item->quantity * (float) $item->unit_price, 2) }}
                                                    </p>

                                                </div>

                                            </div>

                                        @endforeach

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                                ORDER SUMMARY
                            ================================================== --}}
                            <div>

                                <h4 class="mb-3 text-sm font-bold text-slate-900 dark:text-white">
                                    Order Summary
                                </h4>


                                <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800/60">

                                    <dl class="space-y-3 text-sm">

                                        <div class="flex items-center justify-between gap-4">
                                            <dt class="text-slate-500 dark:text-slate-400">
                                                Subtotal
                                            </dt>

                                            <dd class="font-medium text-slate-800 dark:text-slate-200">
                                                {{ $order->currency }}
                                                {{ number_format((float) $order->subtotal, 2) }}
                                            </dd>
                                        </div>


                                        <div class="flex items-center justify-between gap-4">
                                            <dt class="text-slate-500 dark:text-slate-400">
                                                Shipping
                                            </dt>

                                            <dd class="font-medium text-slate-800 dark:text-slate-200">
                                                {{ $order->currency }}
                                                {{ number_format((float) $order->shipping_amount, 2) }}
                                            </dd>
                                        </div>


                                        @if((float) $order->discount_amount > 0)

                                            <div class="flex items-center justify-between gap-4">
                                                <dt class="text-slate-500 dark:text-slate-400">
                                                    Discount
                                                </dt>

                                                <dd class="font-medium text-emerald-600 dark:text-emerald-400">
                                                    -{{ $order->currency }}
                                                    {{ number_format((float) $order->discount_amount, 2) }}
                                                </dd>
                                            </div>

                                        @endif


                                        @if((float) $order->tax_amount > 0)

                                            <div class="flex items-center justify-between gap-4">
                                                <dt class="text-slate-500 dark:text-slate-400">
                                                    Tax
                                                </dt>

                                                <dd class="font-medium text-slate-800 dark:text-slate-200">
                                                    {{ $order->currency }}
                                                    {{ number_format((float) $order->tax_amount, 2) }}
                                                </dd>
                                            </div>

                                        @endif


                                        <div class="border-t border-slate-200 pt-3 dark:border-slate-700">

                                            <div class="flex items-end justify-between gap-4">

                                                <dt class="font-semibold text-slate-900 dark:text-white">
                                                    Total
                                                </dt>

                                                <dd class="text-base font-bold text-slate-900 dark:text-white">
                                                    {{ $order->currency }}
                                                    {{ number_format((float) $order->total_amount, 2) }}
                                                </dd>

                                            </div>

                                        </div>

                                    </dl>

                                </div>


                                {{-- Meta --}}
                                <div class="mt-3 grid grid-cols-2 gap-2">

                                    <div class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 dark:border-slate-700 dark:bg-slate-900">

                                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                                            Items
                                        </p>

                                        <p class="mt-1 text-sm font-bold text-slate-800 dark:text-slate-200">
                                            {{ $itemCount }}
                                        </p>

                                    </div>


                                    <div class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 dark:border-slate-700 dark:bg-slate-900">

                                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                                            Currency
                                        </p>

                                        <p class="mt-1 text-sm font-bold text-slate-800 dark:text-slate-200">
                                            {{ $order->currency }}
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                        FOOTER
                    ================================================== --}}
                    <div class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50/70 px-5 py-4 dark:border-slate-800 dark:bg-slate-800/30 sm:flex-row sm:items-center sm:justify-between">

                        <div class="text-xs text-slate-500 dark:text-slate-400">

                            Order #{{ $order->id }}

                            @if($order->cancelled_at)
                                <span class="mx-1 text-slate-300 dark:text-slate-600">·</span>
                                Cancelled {{ $order->cancelled_at->format('M d, Y') }}
                            @endif

                        </div>


                        <a
                                href="{{ route('customer.orders.show', $order->id) }}"
                                wire:navigate
                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
                        >
                            <span>View Order</span>

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
                                        d="M9 5l7 7-7 7"
                                />
                            </svg>
                        </a>

                    </div>

                </article>

            @endforeach

        </div>


        {{-- =========================================================
            PAGINATION
        ========================================================== --}}
        @if($this->orders->hasPages())

            <div class="pt-2">
                {{ $this->orders->links(data: ['scrollTo' => false]) }}
            </div>

        @endif


    @else

        {{-- =========================================================
            EMPTY STATE
        ========================================================== --}}
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center dark:border-slate-700 dark:bg-slate-900">

            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500">

                <svg class="h-8 w-8"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.5"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"/>
                </svg>

            </div>


            <h3 class="mt-5 text-base font-bold text-slate-900 dark:text-white">
                No orders yet
            </h3>

            <p class="mx-auto mt-1.5 max-w-sm text-sm leading-6 text-slate-500 dark:text-slate-400">
                Your orders will appear here once you complete your first purchase.
            </p>


            {{-- Keep disabled until storefront route exists --}}
            <button
                    type="button"
                    disabled
                    class="mt-6 inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white opacity-50 shadow-sm"
            >
                Start Shopping
            </button>

        </div>

    @endif

</div>