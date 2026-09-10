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

                    <span
                            class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClasses }}"
                    >
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
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-600 shadow-sm transition hover:bg-red-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-red-900/50 dark:bg-slate-900 dark:text-red-400 dark:hover:bg-red-500/10"
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

        {{-- Left Column --}}
        <div class="space-y-6">

            {{-- Order Items --}}
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
                                class="p-5 transition hover:bg-slate-50/50 dark:hover:bg-slate-800/30"
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

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">

                    <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-700">

                        <div class="flex items-center gap-3">

                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">

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

                            <div>

                                <h3 class="font-semibold text-slate-900 dark:text-white">
                                    Shipping Address
                                </h3>

                                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                                    Delivery address for this order
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="p-5">

                        <div class="flex items-start gap-3">

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


                                <div class="mt-3 space-y-1 text-sm text-slate-600 dark:text-slate-300">

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


        {{-- Right Column --}}
        <div class="space-y-6 lg:sticky lg:top-6 lg:self-start">

            {{-- Order Summary --}}
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


                    <div class="my-4 border-t border-dashed border-slate-200 dark:border-slate-700"></div>


                    <div class="flex items-end justify-between gap-4">

                        <div>

                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                                Total
                            </p>

                            <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">
                                {{ $this->order->items->count() }}
                                {{ str()->plural('item', $this->order->items->count()) }}
                            </p>

                        </div>

                        <p class="text-2xl font-bold tracking-tight text-indigo-600 dark:text-indigo-400">
                            {{ number_format((float) $this->order->total_amount, 2) }}

                            <span class="text-sm font-semibold">
                            {{ $this->order->currency }}
                        </span>
                        </p>

                    </div>

                </div>

            </div>


            {{-- Payment --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">

                {{-- Payment Header --}}
                <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-700">

                    <div class="flex items-center justify-between gap-3">

                        <div class="flex items-center gap-3">

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
                                            d="M2.25 8.25h19.5M3.75 5.25h16.5A1.5 1.5 0 0121.75 6.75v10.5a1.5 1.5 0 01-1.5 1.5H3.75a1.5 1.5 0 01-1.5-1.5V6.75a1.5 1.5 0 011.5-1.5z"
                                    />
                                </svg>

                            </div>

                            <div>

                                <h3 class="font-semibold text-slate-900 dark:text-white">
                                    Payment
                                </h3>

                                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                                    Payment details
                                </p>

                            </div>

                        </div>


                        {{-- Payment Status --}}
                        @if ($this->payment?->status === \App\Enums\PaymentStatus::SUCCEEDED)

                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Paid
                        </span>

                        @elseif ($this->payment?->status === \App\Enums\PaymentStatus::FAILED)

                            <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700 dark:bg-red-500/10 dark:text-red-400">
                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                            Failed
                        </span>

                        @elseif ($this->payment)

                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">
                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                            Processing
                        </span>

                        @elseif ($this->order->status->value === 'pending')

                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">
                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                            Unpaid
                        </span>

                        @else

                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                            No Payment
                        </span>

                        @endif

                    </div>

                </div>


                {{-- Payment Content --}}
                <div class="p-5">

                    {{-- Successful Payment --}}
                    @if ($this->payment?->status === \App\Enums\PaymentStatus::SUCCEEDED)

                        <div class="rounded-xl bg-emerald-50/70 p-4 dark:bg-emerald-500/5">

                            <div class="flex items-start gap-3">

                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">

                                    <svg
                                            class="h-5 w-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                    >
                                        <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M5 13l4 4L19 7"
                                        />
                                    </svg>

                                </div>

                                <div>

                                    <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">
                                        Payment successful
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-emerald-700 dark:text-emerald-400">
                                        Your payment has been completed successfully.
                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- Payment Details --}}
                        <div class="mt-5 space-y-3">

                            <div class="flex items-center justify-between gap-4 text-sm">

                            <span class="text-slate-500 dark:text-slate-400">
                                Payment Number
                            </span>

                                <span class="max-w-[170px] truncate font-medium text-slate-900 dark:text-white">
                                {{ $this->payment->payment_number }}
                            </span>

                            </div>


                            <div class="flex items-center justify-between gap-4 text-sm">

                            <span class="text-slate-500 dark:text-slate-400">
                                Amount
                            </span>

                                <span class="font-semibold text-slate-900 dark:text-white">
                                {{ number_format((float) $this->payment->amount, 2) }}
                                    {{ $this->payment->currency }}
                            </span>

                            </div>


                            <div class="flex items-center justify-between gap-4 text-sm">

                            <span class="text-slate-500 dark:text-slate-400">
                                Method
                            </span>

                                <span class="font-medium capitalize text-slate-900 dark:text-white">
                                {{ str_replace('_', ' ', $this->payment->method->value) }}
                            </span>

                            </div>


                            <div class="flex items-center justify-between gap-4 text-sm">

                            <span class="text-slate-500 dark:text-slate-400">
                                Paid At
                            </span>

                                <span class="font-medium text-slate-900 dark:text-white">
                                {{ $this->payment->paid_at?->format('M d, Y H:i') ?? '—' }}
                            </span>

                            </div>


                            @if ($this->payment->transaction_id)

                                <div class="border-t border-slate-100 pt-3 dark:border-slate-800">

                                    <p class="text-xs text-slate-500 dark:text-slate-400">
                                        Transaction ID
                                    </p>

                                    <p class="mt-1 break-all text-xs font-medium text-slate-700 dark:text-slate-300">
                                        {{ $this->payment->transaction_id }}
                                    </p>

                                </div>

                            @endif

                        </div>


                        {{-- Confirm Order --}}
                        @if ($this->order->status->value === 'pending')

                            <button
                                    type="button"
                                    wire:click="confirmPayment"
                                    wire:loading.attr="disabled"
                                    class="mt-5 w-full rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-50 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100"
                            >
                            <span
                                    wire:loading.remove
                                    wire:target="confirmPayment"
                            >
                                Confirm Order
                            </span>

                                <span
                                        wire:loading
                                        wire:target="confirmPayment"
                                >
                                Confirming...
                            </span>
                            </button>

                        @endif


                        {{-- Failed Payment --}}
                    @elseif ($this->payment?->status === \App\Enums\PaymentStatus::FAILED)

                        <div class="rounded-xl bg-red-50/70 p-4 dark:bg-red-500/5">

                            <div class="flex items-start gap-3">

                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-500/10 dark:text-red-400">

                                    <svg
                                            class="h-5 w-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                    >
                                        <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>

                                </div>

                                <div>

                                    <p class="text-sm font-semibold text-red-800 dark:text-red-300">
                                        Payment failed
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-red-700 dark:text-red-400">
                                        The payment could not be completed.
                                    </p>

                                    @if ($this->payment->failure_reason)

                                        <p class="mt-2 text-xs text-red-700 dark:text-red-400">
                                            {{ $this->payment->failure_reason }}
                                        </p>

                                    @endif

                                </div>

                            </div>

                        </div>


                        {{-- Retry Payment --}}
                        @if ($this->order->status->value === 'pending')

                            <div class="mt-5">

                                <label
                                        for="payment-method"
                                        class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300"
                                >
                                    Payment Method
                                </label>

                                <select
                                        id="payment-method"
                                        wire:model="paymentMethod"
                                        class="w-full rounded-xl border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
                                >
                                    <option value="{{ \App\Enums\PaymentMethod::CARD->value }}">
                                        Card
                                    </option>

                                    <option value="{{ \App\Enums\PaymentMethod::BANK_TRANSFER->value }}">
                                        Bank Transfer
                                    </option>

                                    <option value="{{ \App\Enums\PaymentMethod::CASH_ON_DELIVERY->value }}">
                                        Cash on Delivery
                                    </option>

                                    <option value="{{ \App\Enums\PaymentMethod::WALLET->value }}">
                                        Wallet
                                    </option>
                                </select>

                                @error('paymentMethod')

                                <p class="mt-1 text-xs text-red-600">
                                    {{ $message }}
                                </p>

                                @enderror


                                <button
                                        type="button"
                                        wire:click="pay"
                                        wire:loading.attr="disabled"
                                        class="mt-3 w-full rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-50 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100"
                                >
                                <span
                                        wire:loading.remove
                                        wire:target="pay"
                                >
                                    Try Payment Again
                                </span>

                                    <span
                                            wire:loading
                                            wire:target="pay"
                                    >
                                    Processing...
                                </span>
                                </button>

                            </div>

                        @endif


                        {{-- Processing Payment --}}
                    @elseif ($this->payment)

                        <div class="rounded-xl bg-amber-50/70 p-4 dark:bg-amber-500/5">

                            <div class="flex items-start gap-3">

                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400">

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
                                                d="M12 8v4l3 3m0 0a9 9 0 11-6.364-2.636A9 9 0 0118 15"
                                        />
                                    </svg>

                                </div>

                                <div>

                                    <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">
                                        Payment is processing
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-amber-700 dark:text-amber-400">
                                        Your payment has not been completed yet.
                                    </p>

                                    <p class="mt-2 text-xs text-slate-600 dark:text-slate-300">
                                        Payment Number:
                                        <span class="font-semibold">
                                        {{ $this->payment->payment_number }}
                                    </span>
                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- Unpaid Order --}}
                    @elseif ($this->order->status->value === 'pending')

                        <div class="rounded-xl bg-amber-50/70 p-4 dark:bg-amber-500/5">

                            <div class="flex items-start gap-3">

                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400">

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
                                                d="M12 9v3.75m0 3h.007M10.29 3.86l-7.5 13A1.5 1.5 0 004.09 19h15.82a1.5 1.5 0 001.3-2.25l-7.5-13a1.5 1.5 0 00-2.6 0z"
                                        />
                                    </svg>

                                </div>

                                <div>

                                    <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">
                                        Payment required
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-amber-700 dark:text-amber-400">
                                        Complete payment to confirm your order.
                                    </p>

                                </div>

                            </div>

                        </div>


                        <div class="mt-5">

                            <label
                                    for="payment-method"
                                    class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300"
                            >
                                Payment Method
                            </label>

                            <select
                                    id="payment-method"
                                    wire:model="paymentMethod"
                                    class="w-full rounded-xl border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
                            >
                                <option value="{{ \App\Enums\PaymentMethod::CARD->value }}">
                                    Card
                                </option>

                                <option value="{{ \App\Enums\PaymentMethod::BANK_TRANSFER->value }}">
                                    Bank Transfer
                                </option>

                                <option value="{{ \App\Enums\PaymentMethod::CASH_ON_DELIVERY->value }}">
                                    Cash on Delivery
                                </option>

                                <option value="{{ \App\Enums\PaymentMethod::WALLET->value }}">
                                    Wallet
                                </option>
                            </select>

                            @error('paymentMethod')

                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>

                            @enderror


                            <button
                                    type="button"
                                    wire:click="pay"
                                    wire:loading.attr="disabled"
                                    class="mt-3 w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50 dark:bg-indigo-500 dark:hover:bg-indigo-400"
                            >
                            <span
                                    wire:loading.remove
                                    wire:target="pay"
                            >
                                Pay {{ number_format((float) $this->order->total_amount, 2) }}
                                {{ $this->order->currency }}
                            </span>

                                <span
                                        wire:loading
                                        wire:target="pay"
                                >
                                Processing...
                            </span>
                            </button>

                        </div>


                        {{-- No Payment --}}
                    @else

                        <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800/50">

                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                No payment record is available for this order.
                            </p>

                        </div>

                    @endif

                </div>

            </div>


            {{-- Order Metadata --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">

                <div class="px-5 py-4">

                    <div class="space-y-3 text-xs">

                        <div class="flex items-center justify-between gap-4">

                        <span class="text-slate-500 dark:text-slate-400">
                            Order ID
                        </span>

                            <span class="font-medium text-slate-700 dark:text-slate-300">
                            #{{ $this->order->id }}
                        </span>

                        </div>


                        @if ($this->order->confirmed_at)

                            <div class="flex items-center justify-between gap-4">

                            <span class="text-slate-500 dark:text-slate-400">
                                Confirmed
                            </span>

                                <span class="font-medium text-slate-700 dark:text-slate-300">
                                {{ $this->order->confirmed_at->format('M d, Y') }}
                            </span>

                            </div>

                        @endif


                        @if ($this->order->cancelled_at)

                            <div class="flex items-center justify-between gap-4">

                            <span class="text-slate-500 dark:text-slate-400">
                                Cancelled
                            </span>

                                <span class="font-medium text-red-600 dark:text-red-400">
                                {{ $this->order->cancelled_at->format('M d, Y') }}
                            </span>

                            </div>

                        @endif


                        @if ($this->order->completed_at)

                            <div class="flex items-center justify-between gap-4">

                            <span class="text-slate-500 dark:text-slate-400">
                                Completed
                            </span>

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

            const element = button.closest('[wire\\:id]');

            if (!element) {
                console.error('Livewire component element not found.');
                return;
            }

            const component = Livewire.find(
                element.getAttribute('wire:id')
            );

            if (!component) {
                console.error('Livewire component not found.');
                return;
            }

            component.cancel();
        };
    </script>

    @endscript
    ```

</div>
