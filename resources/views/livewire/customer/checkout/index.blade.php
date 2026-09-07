<div class="space-y-6">

    {{-- Header --}}
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
            <h2 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">
                Checkout
            </h2>

            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Review your order and select your delivery address.
            </p>
        </div>

    </div>


    {{-- Empty Cart --}}
    @if ($this->cart->items->isEmpty())

        <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm dark:border-slate-700 dark:bg-slate-900">

            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500">
                <svg
                        class="h-7 w-7"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                >
                    <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 4h12m-8 0a2 2 0 11-4 0m10 0a2 2 0 11-4 0"
                    />
                </svg>
            </div>

            <h3 class="mt-4 text-lg font-semibold text-slate-900 dark:text-white">
                Your cart is empty
            </h3>

            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                Add some products to your cart before checking out.
            </p>

            <a
                    href="{{ route('customer.cart') }}"
                    wire:navigate
                    class="mt-5 inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
            >
                View Cart
            </a>

        </div>

    @else

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px]">

            {{-- Main --}}
            <div class="space-y-6">

                {{-- Delivery Address --}}
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">

                    <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-700">
                        <h3 class="font-semibold text-slate-900 dark:text-white">
                            Delivery Address
                        </h3>

                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            Select the address where your order should be delivered.
                        </p>
                    </div>

                    <div class="space-y-3 p-5">

                        @forelse ($this->addresses as $address)

                            <label
                                    wire:key="checkout-address-{{ $address->id }}"
                                    class="block cursor-pointer"
                            >
                                <input
                                        type="radio"
                                        wire:model.live="addressId"
                                        value="{{ $address->id }}"
                                        class="peer sr-only"
                                >

                                <div class="rounded-xl border border-slate-200 bg-white p-4 transition peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-500/20 hover:border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:hover:border-slate-600">

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

                                        <div class="min-w-0 flex-1">

                                            <div class="flex flex-wrap items-center gap-2">

                                                <p class="font-semibold text-slate-900 dark:text-white">
                                                    {{ $address->recipient_name }}
                                                </p>

                                                @if ($address->label)
                                                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                                        {{ $address->label }}
                                                    </span>
                                                @endif

                                                @if ($address->is_default)
                                                    <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-semibold text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
                                                        Default
                                                    </span>
                                                @endif

                                            </div>

                                            @if ($address->phone)
                                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                                    {{ $address->phone }}
                                                </p>
                                            @endif

                                            <div class="mt-2 space-y-0.5 text-sm text-slate-600 dark:text-slate-300">

                                                @if ($address->address_line)
                                                    <p>{{ $address->address_line }}</p>
                                                @endif

                                                @if ($address->street)
                                                    <p>
                                                        {{ $address->street }}
                                                        @if ($address->building)
                                                            , Building {{ $address->building }}
                                                        @endif
                                                        @if ($address->apartment)
                                                            , Apartment {{ $address->apartment }}
                                                        @endif
                                                    </p>
                                                @endif

                                                @if ($address->area)
                                                    <p>{{ $address->area }}</p>
                                                @endif

                                                @if ($address->city)
                                                    <p>{{ $address->city }}</p>
                                                @endif

                                                @if ($address->country)
                                                    <p>{{ $address->country }}</p>
                                                @endif

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </label>

                        @empty

                            <div class="rounded-xl border border-dashed border-slate-300 p-6 text-center dark:border-slate-700">

                                <p class="text-sm font-medium text-slate-700 dark:text-slate-200">
                                    No delivery address found.
                                </p>

                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                    Please add a delivery address before placing your order.
                                </p>

                            </div>

                        @endforelse

                        @error('addressId')
                        <p class="text-sm font-medium text-red-600 dark:text-red-400">
                            {{ $message }}
                        </p>
                        @enderror

                    </div>

                </div>


                {{-- Order Items --}}
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">

                    <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-700">

                        <div class="flex items-center justify-between gap-4">

                            <div>
                                <h3 class="font-semibold text-slate-900 dark:text-white">
                                    Order Items
                                </h3>

                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                    {{ $this->cart->items->count() }}
                                    {{ str()->plural('item', $this->cart->items->count()) }}
                                </p>
                            </div>

                            <a
                                    href="{{ route('customer.cart') }}"
                                    wire:navigate
                                    class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300"
                            >
                                Edit Cart
                            </a>

                        </div>

                    </div>

                    <div class="divide-y divide-slate-100 dark:divide-slate-800">

                        @foreach ($this->cart->items as $item)

                            <div
                                    wire:key="checkout-item-{{ $item->id }}"
                                    class="p-5"
                            >

                                <div class="flex items-start gap-4">

                                    {{-- Product Image --}}
                                    <div class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-slate-100 dark:bg-slate-800">

                                        @if ($item->productVariant->product?->image)
                                            <img
                                                    src="{{ $item->productVariant->product->image }}"
                                                    alt="{{ $item->productVariant->product->name }}"
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
                                            {{ $item->productVariant->product?->name ?? 'Product' }}
                                        </h4>

                                        <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-500 dark:text-slate-400">

                                            @if ($item->productVariant->sku)
                                                <span>
                                                    SKU: {{ $item->productVariant->sku }}
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
                                                {{ number_format((float) $item->productVariant->price, 2) }}
                                                USD
                                            </span>

                                        </div>

                                    </div>

                                    {{-- Line Total --}}
                                    <div class="shrink-0 text-right">

                                        <p class="font-semibold text-slate-900 dark:text-white">
                                            {{ number_format(
                                                (float) $item->productVariant->price * $item->quantity,
                                                2
                                            ) }}
                                            USD
                                        </p>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

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
                                {{ number_format($this->subtotal, 2) }}
                                USD
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4 text-sm">
                            <span class="text-slate-500 dark:text-slate-400">
                                Shipping
                            </span>

                            <span class="font-medium text-slate-900 dark:text-white">
                                0.00 USD
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4 text-sm">
                            <span class="text-slate-500 dark:text-slate-400">
                                Discount
                            </span>

                            <span class="font-medium text-emerald-600 dark:text-emerald-400">
                                -0.00 USD
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4 text-sm">
                            <span class="text-slate-500 dark:text-slate-400">
                                Tax
                            </span>

                            <span class="font-medium text-slate-900 dark:text-white">
                                0.00 USD
                            </span>
                        </div>

                        <div class="my-4 border-t border-slate-200 dark:border-slate-700"></div>

                        <div class="flex items-center justify-between gap-4">

                            <span class="font-semibold text-slate-900 dark:text-white">
                                Total
                            </span>

                            <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                {{ number_format($this->subtotal, 2) }}
                                USD
                            </span>

                        </div>

                    </div>

                    <div class="border-t border-slate-200 px-5 py-4 dark:border-slate-700">

                        @error('cart')
                        <p class="mb-3 text-sm font-medium text-red-600 dark:text-red-400">
                            {{ $message }}
                        </p>
                        @enderror

                        <button
                                type="button"
                                wire:click="placeOrder"
                                wire:loading.attr="disabled"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50"
                        >

                            <span wire:loading.remove wire:target="placeOrder">
                                Place Order
                            </span>

                            <span wire:loading wire:target="placeOrder">
                                Placing Order...
                            </span>

                        </button>

                        <p class="mt-3 text-center text-xs text-slate-500 dark:text-slate-400">
                            By placing your order, you confirm that the information above is correct.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    @endif

</div>