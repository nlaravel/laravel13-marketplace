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
                                d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5M18.5 3.5a2.121 2.121 0 0 1 3 3L12 16l-4 1 1-4 9.5-9.5Z"
                        />
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">
                        Edit Product
                    </h1>

                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Update the information for {{ $product->name }}.
                    </p>
                </div>

            </div>
        </div>

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
                                    class="h-5 w-5"
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
                                Update the basic information for this product.
                            </p>
                        </div>

                    </div>

                </div>

                <form
                        wire:submit="save"
                        class="divide-y divide-slate-100 dark:divide-slate-800"
                >

                    {{-- Product Details --}}
                    <div class="space-y-6 p-6">

                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                                Product Details
                            </h3>

                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                Update the product name, category, and description.
                            </p>
                        </div>

                        {{-- Product Name --}}
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
                                            d="M5 12h14M12 5l7 7-7 7"
                                    />
                                </svg>

                                Save Changes
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

                                Saving...
                            </span>

                        </button>

                    </div>

                </form>

            </div>

        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Current Product --}}
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
                                    d="M5 4h14a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1Zm3 4h8M8 12h8M8 16h5"
                            />
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <h2 class="text-sm font-semibold text-slate-900 dark:text-white">
                            Current Product
                        </h2>

                        <p class="mt-1 truncate text-xs text-slate-500 dark:text-slate-400">
                            {{ $product->name }}
                        </p>
                    </div>

                </div>

                <div class="mt-5 space-y-3">

                    <div class="flex items-center justify-between gap-4">
                        <span class="text-sm text-slate-500 dark:text-slate-400">
                            Store
                        </span>

                        <span class="truncate text-sm font-medium text-slate-700 dark:text-slate-200">
                            {{ $product->store->name }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <span class="text-sm text-slate-500 dark:text-slate-400">
                            Category
                        </span>

                        <span class="truncate text-sm font-medium text-slate-700 dark:text-slate-200">
                            {{ $product->category->name }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <span class="text-sm text-slate-500 dark:text-slate-400">
                            Status
                        </span>

                        <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                            {{ $product->status->value }}
                        </span>
                    </div>

                </div>

            </div>

            {{-- Update Guidelines --}}
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
                                    d="M12 9v4m0 4h.01M10.29 3.86l-8.18 14A2 2 0 0 0 3.84 21h16.32a2 2 0 0 0 1.73-3.14l-8.18-14a2 2 0 0 0-3.42 0Z"
                            />
                        </svg>
                    </div>

                    <div>
                        <h2 class="text-sm font-semibold text-slate-900 dark:text-white">
                            Update Guidelines
                        </h2>

                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            Keep your product information accurate.
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
                            Keep the category relevant to the product.
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
                            Product status remains unchanged when information is updated.
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
