<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            My Cart
        </h1>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Review the items in your shopping cart.
        </p>
    </div>

    @if ($this->cart->items->isEmpty())

        <div class="rounded-xl border border-gray-200 bg-white p-10 text-center shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
                <svg
                        class="h-8 w-8 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                >
                    <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9h14l-2-9M9 21h.01M15 21h.01"
                    />
                </svg>
            </div>

            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                Your cart is empty
            </h2>

            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                You haven't added any products to your cart yet.
            </p>

        </div>

    @else

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Cart Items --}}
            <div class="space-y-4 lg:col-span-2">

                @foreach ($this->cart->items as $item)

                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                        <div class="flex items-start justify-between gap-4">

                            <div>
                                <h2 class="font-semibold text-gray-900 dark:text-white">
                                    {{ $item->productVariant->product->name }}
                                </h2>

                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    SKU:
                                    {{ $item->productVariant->sku }}
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="font-semibold text-gray-900 dark:text-white">
                                    ${{ number_format((float) $item->productVariant->price, 2) }}
                                </p>

                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    per item
                                </p>
                            </div>

                        </div>

                        <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-4 dark:border-gray-700">

                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                Quantity:
                                <span class="font-semibold">
                                    {{ $item->quantity }}
                                </span>
                            </div>

                            <div class="text-right">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Subtotal
                                </p>

                                <p class="font-semibold text-gray-900 dark:text-white">
                                    ${{ number_format((float) ($item->productVariant->price * $item->quantity), 2) }}
                                </p>
                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

            {{-- Summary --}}
            <div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Order Summary
                    </h2>

                    <div class="mt-5 space-y-3">

                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-300">
                            <span>Items</span>
                            <span>{{ $this->cart->items_count }}</span>
                        </div>

                        <div class="flex justify-between border-t border-gray-100 pt-3 dark:border-gray-700">

                            <span class="font-semibold text-gray-900 dark:text-white">
                                Subtotal
                            </span>

                            <span class="font-semibold text-gray-900 dark:text-white">
                                ${{ number_format((float) $this->cart->items->sum(fn ($item) => $item->productVariant->price * $item->quantity), 2) }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif

</div>