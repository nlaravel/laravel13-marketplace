
<div>
    <div class="mx-auto max-w-7xl p-4 sm:p-6 lg:p-8">

        {{-- Breadcrumb --}}
        <div class="mb-6 flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
            <a
                    href="{{ route('seller.product.index') }}"
                    class="transition hover:text-sky-600 dark:hover:text-sky-400"
            >
                Products
            </a>

            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" />
            </svg>

            <a
                    href="{{ route('seller.product.variants.index', $product) }}"
                    class="transition hover:text-sky-600 dark:hover:text-sky-400"
            >
                Variants
            </a>

            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" />
            </svg>

            <span class="text-slate-900 dark:text-white">
                Edit
            </span>
        </div>

        {{-- Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-600 dark:bg-sky-950/40 dark:text-sky-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.5-7.5a2.121 2.121 0 0 1 3 3L12 14l-4 1 1-4 6.5-6.5z" />
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                        Edit Product Variant
                    </h1>

                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Update the SKU, pricing, and status of this variant.
                    </p>
                </div>
            </div>

            <a
                    href="{{ route('seller.product.variants.index', $product) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0 7-7m-7 7h18" />
                </svg>

                Back to Variants
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Main Form --}}
            <div class="lg:col-span-2">
                <form wire:submit="save">

                    {{-- Product Context --}}
                    <div class="mb-6 rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                            <h2 class="text-base font-semibold text-slate-900 dark:text-white">
                                Product
                            </h2>

                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                The product this variant belongs to.
                            </p>
                        </div>

                        <div class="px-6 py-5">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                                        {{ $product->name }}
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                        {{ $product->store->name }}
                                    </p>
                                </div>

                                <span class="inline-flex items-center rounded-full bg-sky-50 px-2.5 py-1 text-xs font-medium text-sky-700 dark:bg-sky-950/40 dark:text-sky-400">
                                    {{ ucfirst($product->status->value) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Variant Information --}}
                    <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

                        <div class="border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                            <h2 class="text-base font-semibold text-slate-900 dark:text-white">
                                Variant Information
                            </h2>

                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                Update the information used to identify and price this variant.
                            </p>
                        </div>

                        <div class="space-y-6 px-6 py-6">

                            {{-- SKU --}}
                            <div>
                                <label for="sku" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">
                                    SKU
                                </label>

                                <input
                                        id="sku"
                                        type="text"
                                        wire:model="sku"
                                        autocomplete="off"
                                        class="block w-full rounded-lg border px-3 py-2.5 text-sm shadow-sm outline-none transition
                                    @error('sku')
                                        border-red-300 bg-red-50 text-slate-900 focus:border-red-500 focus:ring-red-500 dark:border-red-700 dark:bg-red-900/20 dark:text-white
                                    @else
                                                border-slate-300 bg-white text-slate-900 focus:border-sky-500 focus:ring-sky-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white
                                            @enderror"
                                />

                                @error('sku')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    {{ $message }}
                                </p>
                                @else
                                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                        The SKU must remain unique across all product variants.
                                    </p>
                                    @enderror
                            </div>

                            {{-- Price --}}
                            <div>
                                <label for="price" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">
                                    Price
                                </label>

                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-emerald-600 dark:text-emerald-400">
                                        $
                                    </span>

                                    <input
                                            id="price"
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            wire:model="price"
                                            class="block w-full rounded-lg border px-3 py-2.5 pl-8 text-sm shadow-sm outline-none transition
                                        @error('price')
                                            border-red-300 bg-red-50 text-slate-900 focus:border-red-500 focus:ring-red-500 dark:border-red-700 dark:bg-red-900/20 dark:text-white
                                        @else
                                                    border-slate-300 bg-white text-slate-900 focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white
                                                @enderror"
                                    />
                                </div>

                                @error('price')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            {{-- Compare At Price --}}
                            <div>
                                <label for="compareAtPrice" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">
                                    Compare at Price
                                    <span class="font-normal text-slate-400">(Optional)</span>
                                </label>

                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-amber-600 dark:text-amber-400">
                                        $
                                    </span>

                                    <input
                                            id="compareAtPrice"
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            wire:model="compareAtPrice"
                                            class="block w-full rounded-lg border px-3 py-2.5 pl-8 text-sm shadow-sm outline-none transition
                                        @error('compareAtPrice')
                                            border-red-300 bg-red-50 text-slate-900 focus:border-red-500 focus:ring-red-500 dark:border-red-700 dark:bg-red-900/20 dark:text-white
                                        @else
                                                    border-slate-300 bg-white text-slate-900 focus:border-amber-500 focus:ring-amber-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white
                                                @enderror"
                                    />
                                </div>

                                @error('compareAtPrice')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    {{ $message }}
                                </p>
                                @else
                                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                        Optional original price shown when this variant is discounted.
                                    </p>
                                    @enderror
                            </div>

                            {{-- Active --}}
                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950">
                                <label class="flex cursor-pointer items-start gap-3">
                                    <input
                                            type="checkbox"
                                            wire:model="isActive"
                                            class="mt-0.5 h-4 w-4 rounded border-slate-300 accent-sky-600 focus:ring-sky-500 dark:border-slate-600"
                                    />

                                    <span>
                                        <span class="block text-sm font-medium text-slate-900 dark:text-white">
                                            Active variant
                                        </span>

                                        <span class="mt-1 block text-xs text-slate-500 dark:text-slate-400">
                                            Active variants can be used for sales and inventory operations.
                                        </span>
                                    </span>
                                </label>
                            </div>

                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 px-6 py-5 sm:flex-row sm:justify-end dark:border-slate-800">

                            <a
                                    href="{{ route('seller.product.variants.index', $product) }}"
                                    class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
                            >
                                Cancel
                            </a>

                            <button
                                    type="submit"
                                    wire:loading.attr="disabled"
                                    wire:target="save"
                                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-sky-700 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-sky-500 dark:hover:bg-sky-400"
                            >
                                <svg
                                        wire:loading
                                        wire:target="save"
                                        class="h-4 w-4 animate-spin"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                >
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                                </svg>

                                <span wire:loading.remove wire:target="save">
                                    Update Variant
                                </span>

                                <span wire:loading wire:target="save">
                                    Updating...
                                </span>
                            </button>

                        </div>
                    </div>
                </form>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">

                {{-- Guidelines --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">
                            Variant Guidelines
                        </h2>
                    </div>

                    <div class="space-y-5 px-6 py-6">

                        <div class="flex gap-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-sky-50 text-sky-600 dark:bg-sky-950/40 dark:text-sky-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5l9 9-5 5-9-9V3z" />
                                </svg>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-slate-900 dark:text-white">
                                    Unique SKU
                                </h3>

                                <p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400">
                                    Each variant must have a unique SKU across the marketplace.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-600 dark:bg-amber-950/30 dark:text-amber-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-2.21 0-4 1.343-4 3s1.79 3 4 3 4 1.343 4 3-1.79 3-4 3m0-12V5m0 14v-3m0-8a4 4 0 100-8 4 4 0 000 8z" />
                                </svg>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-slate-900 dark:text-white">
                                    Pricing
                                </h3>

                                <p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400">
                                    Compare-at price should be greater than or equal to the selling price.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 00-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622C17.176 19.29 21 14.591 21 9c0-1.048-.133-2.065-.382-3.016z" />
                                </svg>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-slate-900 dark:text-white">
                                    Active Status
                                </h3>

                                <p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400">
                                    Inactive variants remain stored but are excluded from active selling flows.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Product Structure --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">
                            Product Structure
                        </h2>
                    </div>

                    <div class="px-6 py-6">
                        <div class="space-y-3 text-sm">

                            <div class="flex items-center justify-between">
                                <span class="text-slate-500 dark:text-slate-400">
                                    Product
                                </span>

                                <span class="font-medium text-slate-900 dark:text-white">
                                    {{ $product->name }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-slate-500 dark:text-slate-400">
                                    Variant
                                </span>

                                <span class="font-medium text-slate-900 dark:text-white">
                                    #{{ $variant->id }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-slate-500 dark:text-slate-400">
                                    SKU
                                </span>

                                <span class="font-medium text-sky-600 dark:text-sky-400">
                                    {{ $variant->sku }}
                                </span>
                            </div>

                        </div>

                        <div class="mt-5 rounded-lg bg-slate-50 p-4 dark:bg-slate-950">
                            <p class="text-xs leading-5 text-slate-500 dark:text-slate-400">
                                Product variants contain SKU and pricing information.
                                Inventory is managed separately through the inventory system.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
