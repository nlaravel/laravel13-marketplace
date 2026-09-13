
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

        <div>
            <div class="flex items-center gap-3">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-900 text-white dark:bg-white dark:text-slate-900">
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
                                d="M12 5v14M5 12h14"
                        />
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">
                        Create Product
                    </h1>

                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Add a new product to one of your approved stores.
                    </p>
                </div>

            </div>
        </div>


        {{-- Back --}}
        <a
                href="{{ route('seller.product.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 hover:text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white"
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
                        d="M10 19l-7-7m0 0 7-7m-7 7h18"
                />
            </svg>

            Back to Products
        </a>

    </div>


    {{-- Main Grid --}}
    <div class="grid gap-6 lg:grid-cols-3">

        {{-- Form Card --}}
        <div class="lg:col-span-2">

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

                {{-- Card Header --}}
                <div class="border-b border-slate-200 px-6 py-5 dark:border-slate-800">

                    <div class="flex items-center gap-3">

                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                            <svg
                                    class="h-4.5 w-4.5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                            >
                                <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M4 6h16M4 12h16M4 18h10"
                                />
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-sm font-semibold text-slate-900 dark:text-white">
                                Product Information
                            </h2>

                            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                                Enter the basic information for your product.
                            </p>
                        </div>

                    </div>

                </div>


                <form
                        wire:submit="save"
                        class="divide-y divide-slate-100 dark:divide-slate-800"
                >

                    {{-- Organization --}}
                    <div class="space-y-6 p-6">

                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                                Organization
                            </h3>

                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                Choose the store and category for this product.
                            </p>
                        </div>


                        <div class="grid gap-6 sm:grid-cols-2">

                            {{-- Store --}}
                            <div>

                                <label
                                        for="storeId"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300"
                                >
                                    Store
                                    <span class="text-red-500">*</span>
                                </label>

                                <div class="relative mt-2">

                                    <select
                                            id="storeId"
                                            wire:model="storeId"
                                            class="block w-full appearance-none rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 pr-10 text-sm text-slate-900 shadow-sm outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-100 @error('storeId') border-red-400 focus:border-red-500 focus:ring-red-50 @enderror dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-slate-500 dark:focus:ring-slate-800"
                                    >
                                        <option value="0">
                                            Select a store
                                        </option>

                                        @foreach ($this->stores as $store)
                                            <option value="{{ $store->id }}">
                                                {{ $store->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                        <svg
                                                class="h-4 w-4 text-slate-400"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                        >
                                            <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="1.8"
                                                    d="m6 9 6 6 6-6"
                                            />
                                        </svg>
                                    </div>

                                </div>

                                @error('storeId')
                                <p class="mt-2 text-xs text-red-600 dark:text-red-400">
                                    {{ $message }}
                                </p>
                                @enderror

                            </div>


                            {{-- Category --}}
                            <div>

                                <label
                                        for="categoryId"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300"
                                >
                                    Category
                                    <span class="text-red-500">*</span>
                                </label>

                                <div class="relative mt-2">

                                    <select
                                            id="categoryId"
                                            wire:model="categoryId"
                                            class="block w-full appearance-none rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 pr-10 text-sm text-slate-900 shadow-sm outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-100 @error('categoryId') border-red-400 focus:border-red-500 focus:ring-red-50 @enderror dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-slate-500 dark:focus:ring-slate-800"
                                    >
                                        <option value="0">
                                            Select a category
                                        </option>

                                        @foreach ($this->categories as $category)
                                            <option value="{{ $category->id }}">
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                        <svg
                                                class="h-4 w-4 text-slate-400"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                        >
                                            <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="1.8"
                                                    d="m6 9 6 6 6-6"
                                            />
                                        </svg>
                                    </div>

                                </div>

                                @error('categoryId')
                                <p class="mt-2 text-xs text-red-600 dark:text-red-400">
                                    {{ $message }}
                                </p>
                                @enderror

                            </div>

                        </div>

                    </div>


                    {{-- Basic Information --}}
                    <div class="space-y-6 p-6">

                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                                Basic Information
                            </h3>

                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                Provide a clear name and useful description.
                            </p>
                        </div>


                        {{-- Name --}}
                        <div>

                            <div class="flex items-center justify-between">

                                <label
                                        for="name"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300"
                                >
                                    Product Name
                                    <span class="text-red-500">*</span>
                                </label>

                                <span class="text-xs text-slate-400">
                                    Required
                                </span>

                            </div>

                            <input
                                    id="name"
                                    type="text"
                                    wire:model="name"
                                    autocomplete="off"
                                    placeholder="e.g. Premium Cotton T-Shirt"
                                    class="mt-2 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-slate-400 focus:ring-2 focus:ring-slate-100 @error('name') border-red-400 focus:border-red-500 focus:ring-red-50 @enderror dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-slate-500 dark:focus:ring-slate-800"
                            >

                            @error('name')
                            <p class="mt-2 text-xs text-red-600 dark:text-red-400">
                                {{ $message }}
                            </p>
                            @enderror

                        </div>


                        {{-- Description --}}
                        <div>

                            <div class="flex items-center justify-between">

                                <label
                                        for="description"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300"
                                >
                                    Description
                                </label>

                                <span class="text-xs text-slate-400">
                                    Optional
                                </span>

                            </div>

                            <textarea
                                    id="description"
                                    wire:model="description"
                                    rows="6"
                                    placeholder="Describe the product, its features, materials, or anything customers should know..."
                                    class="mt-2 block w-full resize-y rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm leading-6 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-slate-400 focus:ring-2 focus:ring-slate-100 @error('description') border-red-400 focus:border-red-500 focus:ring-red-50 @enderror dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-slate-500 dark:focus:ring-slate-800"
                            ></textarea>

                            <div class="mt-2 flex justify-end">

                                @error('description')
                                <p class="mr-auto text-xs text-red-600 dark:text-red-400">
                                    {{ $message }}
                                </p>
                                @enderror

                                <span class="text-xs text-slate-400">
                                    Maximum 5,000 characters
                                </span>

                            </div>

                        </div>

                    </div>


                    {{-- Actions --}}
                    <div class="flex flex-col-reverse gap-3 bg-slate-50 px-6 py-4 sm:flex-row sm:items-center sm:justify-end dark:bg-slate-950/40">

                        <a
                                href="{{ route('seller.product.index') }}"
                                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 hover:text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white"
                        >
                            Cancel
                        </a>

                        <button
                                type="submit"
                                wire:loading.attr="disabled"
                                wire:target="save"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100"
                        >

                            <span
                                    wire:loading.remove
                                    wire:target="save"
                                    class="inline-flex items-center gap-2"
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

                                Create Product
                            </span>


                            <span
                                    wire:loading
                                    wire:target="save"
                                    class="inline-flex items-center gap-2"
                            >
                                <svg
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
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4Z"
                                    />
                                </svg>

                                Creating...
                            </span>

                        </button>

                    </div>

                </form>
            </div>

        </div>


        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Guidelines --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">

                <div class="flex items-start gap-3">

                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
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
                                    d="M12 6v12m-6-9h12M6 15h8"
                            />
                        </svg>
                    </div>

                    <div>
                        <h2 class="text-sm font-semibold text-slate-900 dark:text-white">
                            Product Guidelines
                        </h2>

                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            A few tips for better product information.
                        </p>
                    </div>

                </div>


                <div class="mt-5 space-y-4">

                    <div class="flex gap-3">

                        <div class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800">
                            <svg
                                    class="h-3 w-3 text-slate-600 dark:text-slate-300"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                            >
                                <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="m5 12 4 4L19 6"
                                />
                            </svg>
                        </div>

                        <p class="text-sm leading-5 text-slate-600 dark:text-slate-300">
                            Use a clear and descriptive product name.
                        </p>

                    </div>


                    <div class="flex gap-3">

                        <div class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800">
                            <svg
                                    class="h-3 w-3 text-slate-600 dark:text-slate-300"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                            >
                                <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="m5 12 4 4L19 6"
                                />
                            </svg>
                        </div>

                        <p class="text-sm leading-5 text-slate-600 dark:text-slate-300">
                            Choose the correct store and category.
                        </p>

                    </div>


                    <div class="flex gap-3">

                        <div class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800">
                            <svg
                                    class="h-3 w-3 text-slate-600 dark:text-slate-300"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                            >
                                <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="m5 12 4 4L19 6"
                                />
                            </svg>
                        </div>

                        <p class="text-sm leading-5 text-slate-600 dark:text-slate-300">
                            Add useful details that help customers understand the product.
                        </p>

                    </div>

                </div>

            </div>


            {{-- Product Structure --}}
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 dark:border-slate-800 dark:bg-slate-950">

                <div class="flex items-start gap-3">

                    <svg
                            class="mt-0.5 h-5 w-5 shrink-0 text-slate-600 dark:text-slate-300"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                    >
                        <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M4 7h16M4 12h16M4 17h10"
                        />
                    </svg>

                    <div>

                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                            Product Structure
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-400">
                            Product information is created first.
                            Variants, SKU, pricing, and inventory are managed separately.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
