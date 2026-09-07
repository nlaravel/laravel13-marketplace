<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-start gap-3">

            <a
                    href="{{ route('customer.orders.index') }}"
                    wire:navigate
                    class="mt-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800"
                    aria-label="Back to orders"
            >
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
                            d="M15 19l-7-7 7-7"
                    />
                </svg>
            </a>

            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">
                        Order #{{ $this->order->order_number }}
                    </h2>

                    @php
                        $statusClasses = match ($this->order->status->value) {
                            'pending' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
                            'confirmed' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
                            'processing' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400',
                            'partially_shipped', 'shipped' => 'bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-400',
                            'partially_delivered', 'delivered' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
                            'completed' => 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400',
                            'cancelled' => 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400',
                            default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
                        };
                    @endphp

                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClasses }}">
                        {{ str($this->order->status->value)->replace('_', ' ')->title() }}
                    </span>
                </div>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Placed {{ $this->order->created_at?->format('M d, Y \a\t H:i') }}
                </p>
            </div>

        </div>

        @if ($this->order->isCancellable())
            <button
                    type="button"
                    onclick="confirmCancelOrder(this)"
                    wire:loading.attr="disabled"
                    wire:target="cancel"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-red-900/50 dark:bg-slate-900 dark:text-red-400 dark:hover:bg-red-500/10"
            >
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
                            d="M6 18L18 6M6 6l12 12"
                    />
                </svg>

                <span wire:loading.remove wire:target="cancel">
                    Cancel Order
                </span>

                <span wire:loading wire:target="cancel">
                    Cancelling...
                </span>
            </button>
        @endif

    </div>


    {{-- Main Content --}}
    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px]">

        {{-- Items --}}
        <div class="space-y-6">

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">

                <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-700">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-white">
                                Order Items
                            </h3>

                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                {{ $this->order->items->count() }}
                                {{ str()->plural('item', $this->order->items->count()) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800">

                    @foreach ($this->order->items as $item)
                        <div
                                wire:key="order-item-{{ $item->id }}"
                                class="p-5"
                        >

                            <div class="flex items-start gap-4">

                                {{-- Product Image --}}
                                <div class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-slate-100 dark:bg-slate-800">

                                    @if ($item->product?->image)
                                        <img
                                                src="{{ $item->product->image }}"
                                                alt="{{ $item->product_name }}"
                                                class="h-full w-full object-cover"
                                        >
                                    @else
                                        <svg
                                                class="h-7 w-7 text-slate-400"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                        >
                                            <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M20 7l-8-4-8 4m16 0v10l-8 4-8-4V7m16 0l-8 4m-8-4l8 4m0 0v10"
                                            />
                                        </svg>
                                    @endif

                                </div>

                                {{-- Product Info --}}
                                <div class="min-w-0 flex-1">

                                    <h4 class="font-semibold text-slate-900 dark:text-white">
                                        {{ $item->product_name }}
                                    </h4>

                                    <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-500 dark:text-slate-400">

                                        @if ($item->sku)
                                            <span>
                                                SKU: {{ $item->sku }}
                                            </span>
                                        @endif

                                        <span>
                                            Variant #{{ $item->product_variant_id }}
                                        </span>

                                    </div>

                                    <div class="mt-3 flex flex-wrap items-center gap-3 text-sm">

                                        <span class="text-slate-500 dark:text-slate-400">
                                            Qty: {{ $item->quantity }}
                                        </span>

                                        <span class="text-slate-300 dark:text-slate-700">
                                            •
                                        </span>

                                        <span class="font-medium text-slate-700 dark:text-slate-200">
                                            {{ number_format((float) $item->unit_price, 2) }}
                                            {{ $this->order->currency }}
                                        </span>

                                    </div>

                                </div>

                                {{-- Line Total --}}
                                <div class="shrink-0 text-right">

                                    <p class="font-semibold text-slate-900 dark:text-white">
                                        {{ number_format((float) $item->total_amount, 2) }}
                                        {{ $this->order->currency }}
                                    </p>

                                    @if ((float) $item->discount_amount > 0)
                                        <p class="mt-1 text-xs text-emerald-600 dark:text-emerald-400">
                                            Discount:
                                            {{ number_format((float) $item->discount_amount, 2) }}
                                        </p>
                                    @endif

                                </div>

                            </div>

                        </div>
                    @endforeach

                </div>

            </div>


            {{-- Shipping Address --}}
            @if ($this->order->addresses->isNotEmpty())
                @php
                    $shippingAddress = $this->order->addresses->firstWhere('type', 'shipping')
                        ?? $this->order->addresses->first();
                @endphp

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">

                    <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-700">
                        <h3 class="font-semibold text-slate-900 dark:text-white">
                            Shipping Address
                        </h3>
                    </div>

                    <div class="p-5">

                        <div class="flex items-start gap-3">

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
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
                                            d="M17.657 16.657L13.414 21a2 2 0 01-2.828 0l-4.243-4.343a8 8 0 1111.314 0z"
                                    />
                                    <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.8"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                </svg>
                            </div>

                            <div class="min-w-0">

                                @if ($shippingAddress->recipient_name)
                                    <p class="font-semibold text-slate-900 dark:text-white">
                                        {{ $shippingAddress->recipient_name }}
                                    </p>
                                @endif

                                @if ($shippingAddress->phone)
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                        {{ $shippingAddress->phone }}
                                    </p>
                                @endif

                                <div class="mt-2 space-y-0.5 text-sm text-slate-600 dark:text-slate-300">

                                    @foreach ([
                                        $shippingAddress->address_line_1 ?? null,
                                        $shippingAddress->address_line_2 ?? null,
                                        $shippingAddress->city ?? null,
                                        $shippingAddress->state ?? null,
                                        $shippingAddress->postal_code ?? null,
                                        $shippingAddress->country ?? null,
                                    ] as $line)
                                        @if ($line)
                                            <p>{{ $line }}</p>
                                        @endif
                                    @endforeach

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            @endif

        </div>


        {{-- Summary --}}
        <div class="lg:sticky lg:top-6 lg:self-start">

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">

                <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-700">
                    <h3 class="font-semibold text-slate-900 dark:text-white">
                        Order Summary
                    </h3>
                </div>

                <div class="space-y-3 px-5 py-5">

                    <div class="flex items-center justify-between gap-4 text-sm">
                        <span class="text-slate-500 dark:text-slate-400">
                            Subtotal
                        </span>

                        <span class="font-medium text-slate-900 dark:text-white">
                            {{ number_format((float) $this->order->subtotal, 2) }}
                            {{ $this->order->currency }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-4 text-sm">
                        <span class="text-slate-500 dark:text-slate-400">
                            Shipping
                        </span>

                        <span class="font-medium text-slate-900 dark:text-white">
                            {{ number_format((float) $this->order->shipping_amount, 2) }}
                            {{ $this->order->currency }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-4 text-sm">
                        <span class="text-slate-500 dark:text-slate-400">
                            Discount
                        </span>

                        <span class="font-medium text-emerald-600 dark:text-emerald-400">
                            -{{ number_format((float) $this->order->discount_amount, 2) }}
                            {{ $this->order->currency }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-4 text-sm">
                        <span class="text-slate-500 dark:text-slate-400">
                            Tax
                        </span>

                        <span class="font-medium text-slate-900 dark:text-white">
                            {{ number_format((float) $this->order->tax_amount, 2) }}
                            {{ $this->order->currency }}
                        </span>
                    </div>

                    <div class="my-4 border-t border-slate-200 dark:border-slate-700"></div>

                    <div class="flex items-center justify-between gap-4">
                        <span class="font-semibold text-slate-900 dark:text-white">
                            Total
                        </span>

                        <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                            {{ number_format((float) $this->order->total_amount, 2) }}
                            {{ $this->order->currency }}
                        </span>
                    </div>

                </div>

                <div class="border-t border-slate-200 px-5 py-4 dark:border-slate-700">

                    <div class="space-y-2 text-xs text-slate-500 dark:text-slate-400">

                        <div class="flex justify-between gap-4">
                            <span>Order ID</span>
                            <span class="font-medium text-slate-700 dark:text-slate-300">
                                #{{ $this->order->id }}
                            </span>
                        </div>

                        @if ($this->order->confirmed_at)
                            <div class="flex justify-between gap-4">
                                <span>Confirmed</span>
                                <span class="font-medium text-slate-700 dark:text-slate-300">
                                    {{ $this->order->confirmed_at->format('M d, Y') }}
                                </span>
                            </div>
                        @endif

                        @if ($this->order->cancelled_at)
                            <div class="flex justify-between gap-4">
                                <span>Cancelled</span>
                                <span class="font-medium text-red-600 dark:text-red-400">
                                    {{ $this->order->cancelled_at->format('M d, Y') }}
                                </span>
                            </div>
                        @endif

                        @if ($this->order->completed_at)
                            <div class="flex justify-between gap-4">
                                <span>Completed</span>
                                <span class="font-medium text-emerald-600 dark:text-emerald-400">
                                    {{ $this->order->completed_at->format('M d, Y') }}
                                </span>
                            </div>
                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>
    @script
    <script>
        window.confirmCancelOrder = async function (button) {
            const confirmed = await confirmDelete(
                'This order will be cancelled.'
            );

            if (!confirmed) {
                return;
            }

            const component = Livewire.find(
                button.closest('[wire\\:id]').getAttribute('wire:id')
            );

            if (!component) {
                console.error('Livewire component not found.');
                return;
            }

            component.cancel();
        };
    </script>
    @endscript
</div>