<div>
    <div class="mx-auto max-w-7xl p-4 sm:p-6 lg:p-8">

        {{-- Page Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-600 dark:bg-sky-950/40 dark:text-sky-400">
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
                                d="M20 7.5 12 3 4 7.5m16 0v9L12 21l-8-4.5v-9m16 0-8 4.5m-8-4.5 8 4.5m0 0V21"
                        />
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                        Product Variants
                    </h1>

                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Manage variants, SKUs, pricing, and availability for this product.
                    </p>
                </div>
            </div>

            <a
                    href="{{ route('seller.product.variants.create', $product) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-400"
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
                            d="M12 4v16m8-8H4"
                    />
                </svg>

                Add Variant
            </a>
        </div>

        {{-- Product Context --}}
        <div class="mb-6 rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-col gap-4 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">
                            {{ $product->name }}
                        </h2>

                        <span class="inline-flex items-center rounded-full bg-sky-50 px-2.5 py-1 text-xs font-medium text-sky-700 dark:bg-sky-950/40 dark:text-sky-400">
                            {{ $this->variants->count() }} {{ Str::plural('Variant', $this->variants->count()) }}
                        </span>
                    </div>

                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        {{ $product->store->name }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <span
                            class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium
                        @if($product->status->value === 'active')
                                    bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400
@elseif($product->status->value === 'draft')
                                    bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300
@else
                                    bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400
@endif"
                    >
                        {{ ucfirst($product->status->value) }}
                    </span>

                    <a
                            href="{{ route('seller.product.edit', $product) }}"
                            class="text-sm font-medium text-slate-600 transition hover:text-sky-600 dark:text-slate-300 dark:hover:text-sky-400"
                    >
                        Edit Product
                    </a>
                </div>
            </div>
        </div>

        @if($this->variants->isEmpty())

            {{-- Empty State --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex flex-col items-center px-6 py-16 text-center">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-50 text-sky-600 dark:bg-sky-950/40 dark:text-sky-400">
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
                                    d="M20 7.5 12 3 4 7.5m16 0v9L12 21l-8-4.5v-9m16 0-8 4.5m0 0V21"
                            />
                        </svg>
                    </div>

                    <h3 class="mt-5 text-base font-semibold text-slate-900 dark:text-white">
                        No variants yet
                    </h3>

                    <p class="mt-2 max-w-md text-sm leading-6 text-slate-500 dark:text-slate-400">
                        Add your first product variant to define its SKU, price, and availability.
                    </p>

                    <a
                            href="{{ route('seller.product.variants.create', $product) }}"
                            class="mt-6 inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-400"
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
                                    d="M12 4v16m8-8H4"
                            />
                        </svg>

                        Add First Variant
                    </a>
                </div>
            </div>

        @else

            {{-- Variants Table --}}
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                        <thead class="bg-slate-50 dark:bg-slate-950">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                SKU
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Price
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Compare at
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Status
                            </th>

                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Actions
                            </th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        @foreach($this->variants as $variant)
                            <tr
                                    wire:key="variant-{{ $variant->id }}"
                                    class="transition hover:bg-slate-50 dark:hover:bg-slate-800/40"
                            >
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-sky-50 text-sky-600 dark:bg-sky-950/40 dark:text-sky-400">
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
                                                        d="M7 7h.01M7 3h5l9 9-5 5-9-9V3z"
                                                />
                                            </svg>
                                        </div>

                                        <span class="text-sm font-semibold text-slate-900 dark:text-white">
                                                {{ $variant->sku }}
                                            </span>
                                    </div>
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-emerald-600 dark:text-emerald-400">
                                    ${{ number_format((float) $variant->price, 2) }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                                    @if($variant->compare_at_price !== null)
                                        ${{ number_format((float) $variant->compare_at_price, 2) }}
                                    @else
                                        <span class="text-slate-400 dark:text-slate-500">
                                                —
                                            </span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">
                                    @if($variant->is_active)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                Active
                                            </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                                Inactive
                                            </span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-right">
                                    <a
                                            href="{{ route('seller.product.variants.edit', [
                                                'product' => $product,
                                                'variant' => $variant,
                                            ]) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-sky-50 hover:text-sky-600 dark:text-slate-300 dark:hover:bg-sky-950/30 dark:hover:text-sky-400"
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
                                                    d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.5-7.5a2.121 2.121 0 0 1 3 3L12 14l-4 1 1-4 6.5-6.5z"
                                            />
                                        </svg>

                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        @endif

        {{-- Information --}}
        <div class="mt-6 rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex gap-4 px-6 py-5">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-sky-50 text-sky-600 dark:bg-sky-950/40 dark:text-sky-400">
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
                                d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18z"
                        />
                    </svg>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                        About Product Variants
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-slate-500 dark:text-slate-400">
                        A product can have multiple variants. Each variant has its own SKU and
                        pricing information, while inventory is managed separately through the
                        inventory system.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
