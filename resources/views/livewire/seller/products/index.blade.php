
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

        <div>
            <div class="flex items-center gap-3">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-900 text-white shadow-sm dark:bg-white dark:text-slate-900">
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
                                d="M20 7l-8-4-8 4m16 0v10l-8 4m8-14l-8 4m0 0L4 7m8 4v10"
                        />
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">
                        Products
                    </h1>

                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Manage your products and product information.
                    </p>
                </div>

            </div>
        </div>

        <a
                href="{{ route('seller.product.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100"
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
                        d="M12 5v14M5 12h14"
                />
            </svg>

            Add Product
        </a>

    </div>


    {{-- Summary --}}
    <div class="grid gap-4 sm:grid-cols-2">

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Total Products
                    </p>

                    <p class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 dark:text-white">
                        {{ $this->products->count() }}
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
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
                                d="M20 7l-8-4-8 4m16 0v10l-8 4m8-14l-8 4m0 0L4 7m8 4v10"
                        />
                    </svg>
                </div>

            </div>

            <div class="mt-4 h-px bg-slate-100 dark:bg-slate-800"></div>

            <p class="mt-3 text-xs text-slate-400 dark:text-slate-500">
                Products currently managed by your account.
            </p>

        </div>

    </div>


    {{-- Products --}}
    @if ($this->products->isEmpty())

        {{-- Empty State --}}
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center shadow-sm dark:border-slate-700 dark:bg-slate-900">

            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-300">

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
                            d="M20 7l-8-4-8 4m16 0v10l-8 4m8-14l-8 4m0 0L4 7m8 4v10"
                    />
                </svg>

            </div>

            <h2 class="mt-5 text-lg font-semibold text-slate-900 dark:text-white">
                No products yet
            </h2>

            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500 dark:text-slate-400">
                Start adding products to your store and manage them from this page.
            </p>

            <a
                    href="{{ route('seller.product.create') }}"
                    class="mt-6 inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100"
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
                            d="M12 5v14M5 12h14"
                    />
                </svg>

                Add Your First Product
            </a>

        </div>

    @else

        {{-- Product Grid --}}
        <div class="grid gap-5 xl:grid-cols-2">

            @foreach ($this->products as $product)

                <div
                        wire:key="product-{{ $product->id }}"
                        class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md dark:border-slate-800 dark:bg-slate-900"
                >

                    {{-- Card Header --}}
                    <div class="border-b border-slate-100 px-6 py-5 dark:border-slate-800">

                        <div class="flex items-start justify-between gap-4">

                            <div class="flex min-w-0 items-center gap-4">

                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300">

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
                                                d="M20 7l-8-4-8 4m16 0v10l-8 4m8-14l-8 4m0 0L4 7m8 4v10"
                                        />
                                    </svg>

                                </div>

                                <div class="min-w-0">

                                    <h2 class="truncate text-base font-semibold text-slate-900 dark:text-white">
                                        {{ $product->name }}
                                    </h2>

                                    <p class="mt-1 truncate text-xs text-slate-400 dark:text-slate-500">
                                        /{{ $product->slug }}
                                    </p>

                                </div>

                            </div>


                            {{-- Status --}}
                            @php
                                $status = $product->status->value;
                            @endphp

                            <span
                                    class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium
                                @if ($status === 'active')
                                            bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400
@elseif ($status === 'pending')
                                            bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-400
@elseif ($status === 'rejected')
                                            bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400
@else
                                            bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300
@endif"
                            >
                                <span
                                        class="h-1.5 w-1.5 rounded-full
                                    @if ($status === 'active')
                                                bg-emerald-500
@elseif ($status === 'pending')
                                                bg-amber-500
@elseif ($status === 'rejected')
                                                bg-red-500
@else
                                                bg-slate-400
@endif"
                                ></span>

                                {{ ucfirst($status) }}
                            </span>

                        </div>

                    </div>


                    {{-- Card Body --}}
                    <div class="px-6 py-5">

                        {{-- Description --}}
                        <div>

                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                Description
                            </p>

                            <p class="mt-2 min-h-[3rem] line-clamp-2 text-sm leading-6 text-slate-600 dark:text-slate-300">
                                {{ $product->description ?: 'No description provided.' }}
                            </p>

                        </div>


                        {{-- Meta --}}
                        <div class="mt-6 grid grid-cols-2 gap-4">

                            <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-950">

                                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                    Store
                                </p>

                                <p class="mt-1.5 truncate text-sm font-medium text-slate-800 dark:text-slate-200">
                                    {{ $product->store?->name ?? '—' }}
                                </p>

                            </div>

                            <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-950">

                                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                    Category
                                </p>

                                <p class="mt-1.5 truncate text-sm font-medium text-slate-800 dark:text-slate-200">
                                    {{ $product->category?->name ?? '—' }}
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Footer --}}
                    <div class="flex items-center justify-between border-t border-slate-100 px-6 py-4 dark:border-slate-800">

                        <div class="flex items-center gap-2 text-xs text-slate-400 dark:text-slate-500">

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
                                        d="M8 7V3m8 4V3M4 11h16M5 21h14a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h1Z"
                                />
                            </svg>

                            Created {{ $product->created_at->diffForHumans() }}

                        </div>

                        <a
                                href="{{ route('seller.product.edit', $product) }}"
                                class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-200 dark:hover:bg-slate-800 dark:hover:text-white"
                        >
                            Manage

                            <svg
                                    class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-0.5"
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

                </div>

            @endforeach

        </div>

    @endif

</div>
