<div class="min-h-full">

    {{-- Header --}}
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-start gap-4">

            {{-- Icon --}}
            <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl
                       bg-indigo-50 text-indigo-600
                       dark:bg-indigo-500/10 dark:text-indigo-400"
            >
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
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13 5.4 5M7 13l-1 2a1 1 0 0 0 .9 1.5h10.6M10 20a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm9 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"
                    />
                </svg>
            </div>

            {{-- Title --}}
            <div>
                <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">
                    My Cart
                </h1>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Review your items and manage your shopping cart.
                </p>
            </div>

        </div>


        {{-- Clear Cart --}}
        @if ($this->cart->items->isNotEmpty())

            <button
                    type="button"
                    onclick="confirmClearCart(this)"
                    wire:loading.attr="disabled"
                    wire:target="clearCart"
                    class="group inline-flex items-center justify-center gap-2 rounded-xl
                       border border-slate-200 bg-white px-4 py-2.5
                       text-sm font-medium text-slate-600
                       shadow-sm transition-all duration-200
                       hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600
                       focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2
                       disabled:cursor-not-allowed disabled:opacity-60
                       dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300
                       dark:hover:border-rose-500/30 dark:hover:bg-rose-500/10
                       dark:hover:text-rose-400 dark:focus:ring-offset-slate-900"
            >

                {{-- Trash Icon --}}
                <svg
                        wire:loading.remove
                        wire:target="clearCart"
                        class="h-4 w-4 transition-transform duration-200 group-hover:scale-110"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                >
                    <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m-9 0 1 14h8l1-14M10 11v5m4-5v5"
                    />
                </svg>


                {{-- Loading --}}
                <svg
                        wire:loading
                        wire:target="clearCart"
                        class="h-4 w-4 animate-spin"
                        fill="none"
                        viewBox="0 0 24 24"
                >
                    <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                    />

                    <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                    />
                </svg>


                <span wire:loading.remove wire:target="clearCart">
                    Clear Cart
                </span>

                <span wire:loading wire:target="clearCart">
                    Clearing...
                </span>

            </button>

        @endif

    </div>


    {{-- Empty State --}}
    @if ($this->cart->items->isEmpty())

        <div
                class="flex min-h-[420px] flex-col items-center justify-center
                   rounded-2xl border border-dashed border-slate-300
                   bg-white px-6 py-12 text-center shadow-sm
                   dark:border-slate-700 dark:bg-slate-800"
        >

            <div
                    class="flex h-16 w-16 items-center justify-center rounded-2xl
                       bg-slate-100 text-slate-500
                       dark:bg-slate-700 dark:text-slate-400"
            >
                <svg
                        class="h-8 w-8"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                >
                    <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.7"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13 5.4 5M7 13l-1 2a1 1 0 0 0 .9 1.5h10.6M10 20a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm9 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"
                    />
                </svg>
            </div>


            <h2 class="mt-5 text-lg font-semibold text-slate-900 dark:text-white">
                Your cart is empty
            </h2>


            <p class="mt-2 max-w-md text-sm leading-6 text-slate-500 dark:text-slate-400">
                You haven't added any products to your cart yet.
                Browse our products and add something you love.
            </p>


            <a
                    href="{{ route('customer.products.index') }}"
                    wire:navigate
                    class="mt-6 inline-flex items-center justify-center gap-2
                       rounded-xl bg-indigo-600 px-4 py-2.5
                       text-sm font-medium text-white shadow-sm
                       transition hover:bg-indigo-700
                       focus:outline-none focus:ring-2 focus:ring-indigo-500
                       focus:ring-offset-2 dark:focus:ring-offset-slate-900"
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
                            d="M12 5v14m-7-7h14"
                    />
                </svg>

                Browse Products

            </a>

        </div>

    @else


        {{-- Cart + Summary --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">


            {{-- Cart Items --}}
            <div class="space-y-4 lg:col-span-2">

                @foreach ($this->cart->items as $item)

                    @php
                        $variant = $item->productVariant;
                        $product = $variant?->product;
                        $inventory = $variant?->inventory;

                        $availableQuantity = $inventory
                            ? max(
                                0,
                                $inventory->quantity - $inventory->reserved_quantity
                            )
                            : 0;

                        $lineTotal =
                            $item->quantity *
                            (float) $variant->price;

                        $hasDiscount =
                            $variant->compare_at_price &&
                            (float) $variant->compare_at_price >
                            (float) $variant->price;
                    @endphp


                    {{-- Product Card --}}
                    <div
                            wire:key="cart-item-{{ $item->id }}"
                            class="group overflow-hidden rounded-2xl
                               border border-slate-200 bg-white shadow-sm
                               transition-shadow duration-200
                               hover:shadow-md
                               dark:border-slate-700 dark:bg-slate-800"
                    >

                        <div class="p-5 sm:p-6">

                            {{-- Product Top --}}
                            <div class="flex gap-4">


                                {{-- Product Image --}}
                                <div
                                        class="h-24 w-24 shrink-0 overflow-hidden rounded-xl
                                           bg-slate-100 ring-1 ring-slate-200/70
                                           dark:bg-slate-700 dark:ring-slate-600"
                                >

                                    @if ($product?->image)

                                        <img
                                                src="{{ $product->image }}"
                                                alt="{{ $product->name }}"
                                                class="h-full w-full object-cover transition-transform
                                                   duration-300 group-hover:scale-105"
                                        >

                                    @else

                                        <div
                                                class="flex h-full w-full items-center justify-center
                                                   text-slate-400 dark:text-slate-500"
                                        >
                                            <svg
                                                    class="h-9 w-9"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                            >
                                                <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M4 5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5Zm3 11 3-3 2 2 3-4 3 5M8 8h.01"
                                                />
                                            </svg>
                                        </div>

                                    @endif

                                </div>


                                {{-- Product Details --}}
                                <div class="min-w-0 flex-1">

                                    <div class="flex items-start justify-between gap-3">


                                        <div class="min-w-0">

                                            <h2
                                                    class="truncate text-base font-semibold
                                                       text-slate-900 dark:text-white"
                                            >
                                                {{ $product?->name ?? 'Product' }}
                                            </h2>

                                            <p
                                                    class="mt-1 text-sm text-slate-500
                                                       dark:text-slate-400"
                                            >
                                                SKU: {{ $variant->sku }}
                                            </p>

                                        </div>


                                        {{-- Remove Button --}}
                                        <button
                                                type="button"
                                                onclick="confirmRemoveCartItem({{ $item->id }}, this)"
                                                wire:loading.attr="disabled"
                                                wire:target="remove({{ $item->id }})"
                                                class="group/delete inline-flex h-9 w-9 shrink-0
                                                   items-center justify-center rounded-xl
                                                   border border-slate-200 bg-white
                                                   text-slate-400 shadow-sm
                                                   transition-all duration-200
                                                   hover:border-rose-200
                                                   hover:bg-rose-50
                                                   hover:text-rose-600
                                                   hover:shadow-none
                                                   focus:outline-none
                                                   focus:ring-2 focus:ring-rose-500
                                                   focus:ring-offset-2
                                                   disabled:cursor-not-allowed
                                                   disabled:opacity-50
                                                   dark:border-slate-600
                                                   dark:bg-slate-800
                                                   dark:text-slate-400
                                                   dark:hover:border-rose-500/30
                                                   dark:hover:bg-rose-500/10
                                                   dark:hover:text-rose-400
                                                   dark:focus:ring-offset-slate-800"
                                                aria-label="Remove item"
                                                title="Remove item"
                                        >

                                            {{-- X --}}
                                            <svg
                                                    wire:loading.remove
                                                    wire:target="remove({{ $item->id }})"
                                                    class="h-4 w-4 transition-transform duration-200
                                                       group-hover/delete:scale-110"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                            >
                                                <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="1.8"
                                                        d="M6 6l12 12M18 6 6 18"
                                                />
                                            </svg>


                                            {{-- Loading --}}
                                            <svg
                                                    wire:loading
                                                    wire:target="remove({{ $item->id }})"
                                                    class="h-4 w-4 animate-spin"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                            >
                                                <circle
                                                        class="opacity-25"
                                                        cx="12"
                                                        cy="12"
                                                        r="10"
                                                        stroke="currentColor"
                                                        stroke-width="4"
                                                />

                                                <path
                                                        class="opacity-75"
                                                        fill="currentColor"
                                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                                                />
                                            </svg>

                                        </button>

                                    </div>


                                    {{-- Price --}}
                                    <div class="mt-3 flex items-center gap-2">

                                        <span
                                                class="text-sm font-semibold
                                                   text-slate-900 dark:text-white"
                                        >
                                            ${{ number_format((float) $variant->price, 2) }}
                                        </span>

                                        @if ($hasDiscount)

                                            <span
                                                    class="text-xs text-slate-400 line-through
                                                       dark:text-slate-500"
                                            >
                                                ${{ number_format((float) $variant->compare_at_price, 2) }}
                                            </span>

                                        @endif

                                    </div>


                                    {{-- Stock Status --}}
                                    <div class="mt-2">

                                        @if ($availableQuantity > 0)

                                            <span
                                                    class="inline-flex items-center gap-1.5
                                                       rounded-lg
                                                       bg-emerald-50 px-2.5 py-1
                                                       text-xs font-medium
                                                       text-emerald-700
                                                       ring-1 ring-inset
                                                       ring-emerald-600/10
                                                       dark:bg-emerald-500/10
                                                       dark:text-emerald-400
                                                       dark:ring-emerald-400/20"
                                            >

                                                <span
                                                        class="h-1.5 w-1.5 rounded-full
                                                           bg-emerald-500"
                                                ></span>

                                                @if ($availableQuantity <= 5)
                                                    Only {{ $availableQuantity }} left
                                                @else
                                                    In stock
                                                @endif

                                            </span>

                                        @else

                                            <span
                                                    class="inline-flex items-center gap-1.5
                                                       rounded-lg
                                                       bg-rose-50 px-2.5 py-1
                                                       text-xs font-medium
                                                       text-rose-700
                                                       ring-1 ring-inset
                                                       ring-rose-600/10
                                                       dark:bg-rose-500/10
                                                       dark:text-rose-400
                                                       dark:ring-rose-400/20"
                                            >

                                                <span
                                                        class="h-1.5 w-1.5 rounded-full
                                                           bg-rose-500"
                                                ></span>

                                                Out of stock

                                            </span>

                                        @endif

                                    </div>

                                </div>

                            </div>


                            {{-- Footer --}}
                            <div
                                    class="mt-5 flex flex-col gap-4 border-t
                                       border-slate-200 pt-5
                                       sm:flex-row sm:items-center
                                       sm:justify-between
                                       dark:border-slate-700"
                            >


                                {{-- Quantity --}}
                                <div class="flex items-center gap-3">

                                    <span
                                            class="text-sm font-medium
                                               text-slate-600 dark:text-slate-300"
                                    >
                                        Quantity
                                    </span>


                                    <div
                                            class="flex items-center overflow-hidden
                                               rounded-xl border border-slate-200
                                               bg-slate-50
                                               dark:border-slate-600
                                               dark:bg-slate-700"
                                    >

                                        {{-- Decrement --}}
                                        <button
                                                type="button"
                                                wire:click="decrement({{ $item->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="decrement({{ $item->id }})"
                                                @disabled($item->quantity <= 1)
                                            class="flex h-9 w-9 items-center justify-center
                                            text-slate-600 transition
                                            hover:bg-slate-100
                                            disabled:cursor-not-allowed
                                            disabled:opacity-40
                                            dark:text-slate-300
                                            dark:hover:bg-slate-600"
                                            aria-label="Decrease quantity"
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
                                                        stroke-width="2"
                                                        d="M5 12h14"
                                                />
                                            </svg>
                                        </button>


                                        {{-- Quantity --}}
                                        <span
                                                class="flex h-9 min-w-10 items-center
                                                   justify-center border-x
                                                   border-slate-200 px-2
                                                   text-sm font-semibold
                                                   text-slate-900
                                                   dark:border-slate-600
                                                   dark:text-white"
                                        >
                                            {{ $item->quantity }}
                                        </span>


                                        {{-- Increment --}}
                                        <button
                                                type="button"
                                                wire:click="increment({{ $item->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="increment({{ $item->id }})"
                                                @disabled($item->quantity >= $availableQuantity)
                                            class="flex h-9 w-9 items-center justify-center
                                            text-slate-600 transition
                                            hover:bg-slate-100
                                            disabled:cursor-not-allowed
                                            disabled:opacity-40
                                            dark:text-slate-300
                                            dark:hover:bg-slate-600"
                                            aria-label="Increase quantity"
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
                                                        stroke-width="2"
                                                        d="M12 5v14M5 12h14"
                                                />
                                            </svg>
                                        </button>

                                    </div>

                                </div>


                                {{-- Line Total --}}
                                <div class="text-left sm:text-right">

                                    <p
                                            class="text-xs text-slate-500
                                               dark:text-slate-400"
                                    >
                                        Item total
                                    </p>

                                    <p
                                            class="mt-1 text-lg font-semibold
                                               text-slate-900 dark:text-white"
                                    >
                                        ${{ number_format($lineTotal, 2) }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>


            {{-- Order Summary --}}
            <div class="lg:col-span-1">

                <div
                        class="sticky top-24 overflow-hidden rounded-2xl
                           border border-slate-200 bg-white shadow-sm
                           dark:border-slate-700 dark:bg-slate-800"
                >

                    {{-- Summary Header --}}
                    <div
                            class="border-b border-slate-200 px-5 py-4
                               dark:border-slate-700"
                    >
                        <h2
                                class="text-base font-semibold
                                   text-slate-900 dark:text-white"
                        >
                            Order Summary
                        </h2>
                    </div>


                    <div class="space-y-4 p-5">

                        @php

                            $subtotal = $this->cart->items->sum(
                                fn ($item) =>
                                    $item->quantity *
                                    (float) $item->productVariant->price
                            );

                            $itemsCount = $this->cart->items->sum(
                                fn ($item) => $item->quantity
                            );

                        @endphp


                        {{-- Items --}}
                        <div class="flex items-center justify-between text-sm">

                            <span class="text-slate-500 dark:text-slate-400">
                                Items
                            </span>

                            <span
                                    class="font-medium text-slate-900
                                       dark:text-white"
                            >
                                {{ $itemsCount }}
                            </span>

                        </div>


                        {{-- Subtotal --}}
                        <div class="flex items-center justify-between text-sm">

                            <span class="text-slate-500 dark:text-slate-400">
                                Subtotal
                            </span>

                            <span
                                    class="font-medium text-slate-900
                                       dark:text-white"
                            >
                                ${{ number_format($subtotal, 2) }}
                            </span>

                        </div>


                        {{-- Shipping --}}
                        <div class="flex items-start justify-between gap-4 text-sm">

                            <span class="text-slate-500 dark:text-slate-400">
                                Shipping
                            </span>

                            <span
                                    class="text-right text-xs font-medium
                                       text-slate-500 dark:text-slate-400"
                            >
                                Calculated at checkout
                            </span>

                        </div>


                        {{-- Total --}}
                        <div
                                class="border-t border-slate-200 pt-4
                                   dark:border-slate-700"
                        >

                            <div class="flex items-center justify-between">

                                <span
                                        class="text-base font-semibold
                                           text-slate-900 dark:text-white"
                                >
                                    Total
                                </span>

                                <span
                                        class="text-xl font-bold
                                           text-slate-900 dark:text-white"
                                >
                                    ${{ number_format($subtotal, 2) }}
                                </span>

                            </div>


                            <p
                                    class="mt-2 text-xs leading-5
                                       text-slate-500 dark:text-slate-400"
                            >
                                Shipping, taxes, and final charges will be
                                calculated during checkout.
                            </p>

                        </div>


                        {{-- Checkout --}}
                        <button
                                type="button"
                                disabled
                                class="mt-2 flex w-full cursor-not-allowed
                                   items-center justify-center gap-2
                                   rounded-xl bg-indigo-600 px-4 py-3
                                   text-sm font-semibold text-white
                                   opacity-60"
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
                                        d="M5 12h14M13 6l6 6-6 6"
                                />
                            </svg>

                            Checkout

                        </button>


                        <p
                                class="text-center text-xs
                                   text-slate-400 dark:text-slate-500"
                        >
                            Checkout will be available once the checkout
                            module is ready.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- SweetAlert --}}
    @script

    <script>

        window.confirmRemoveCartItem = async function (itemId, button) {

            const confirmed = await confirmDelete(
                'This item will be permanently removed from your cart.'
            );

            if (!confirmed) {
                return;
            }

            const wireId = button
                .closest('[wire\\:id]')
                ?.getAttribute('wire:id');

            if (!wireId) {
                console.error('Livewire component not found.');
                return;
            }

            const component = Livewire.find(wireId);

            if (!component) {
                console.error('Livewire component not found.');
                return;
            }

            component.remove(itemId);
        };


        window.confirmClearCart = async function (button) {

            const confirmed = await confirmDelete(
                'All items will be permanently removed from your cart.'
            );

            if (!confirmed) {
                return;
            }

            const wireId = button
                .closest('[wire\\:id]')
                ?.getAttribute('wire:id');

            if (!wireId) {
                console.error('Livewire component not found.');
                return;
            }

            const component = Livewire.find(wireId);

            if (!component) {
                console.error('Livewire component not found.');
                return;
            }

            component.clearCart();
        };

    </script>

    @endscript

</div>