
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-3">
                <a
                        href="{{ route('seller.store') }}"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl
                    border border-slate-200 bg-white text-slate-600 transition
                    hover:bg-slate-50 hover:text-slate-900
                    dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300
                    dark:hover:bg-slate-800 dark:hover:text-white"
                        aria-label="Back to stores"
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
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                        />
                    </svg>
                </a>

                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                        Create Store
                    </h1>

                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Create your store and submit it for approval.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="max-w-3xl">
        <form
                wire:submit="save"
                class="overflow-hidden rounded-2xl border border-slate-200 bg-white
            shadow-sm dark:border-slate-800 dark:bg-slate-900"
        >
            <div class="border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                    Store Information
                </h2>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Enter the basic information for your store.
                </p>
            </div>

            <div class="space-y-6 p-6">
                {{-- Store Name --}}
                <div>
                    <label
                            for="name"
                            class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200"
                    >
                        Store Name
                    </label>

                    <input
                            id="name"
                            type="text"
                            wire:model="name"
                            placeholder="Enter your store name"
                            maxlength="255"
                            class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3
                        text-sm text-slate-900 outline-none transition
                        placeholder:text-slate-400
                        focus:border-slate-500 focus:ring-2 focus:ring-slate-200
                        dark:border-slate-700 dark:bg-slate-950 dark:text-white
                        dark:placeholder:text-slate-500 dark:focus:border-slate-500
                        dark:focus:ring-slate-800"
                    >

                    @error('name')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label
                            for="description"
                            class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200"
                    >
                        Description
                    </label>

                    <textarea
                            id="description"
                            wire:model="description"
                            rows="5"
                            maxlength="5000"
                            placeholder="Describe your store..."
                            class="block w-full resize-y rounded-xl border border-slate-300 bg-white px-4 py-3
                        text-sm text-slate-900 outline-none transition
                        placeholder:text-slate-400
                        focus:border-slate-500 focus:ring-2 focus:ring-slate-200
                        dark:border-slate-700 dark:bg-slate-950 dark:text-white
                        dark:placeholder:text-slate-500 dark:focus:border-slate-500
                        dark:focus:ring-slate-800"
                    ></textarea>

                    @error('description')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Approval Notice --}}
                <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-900/50 dark:bg-amber-950/30">
                    <div class="flex gap-3">
                        <svg
                                class="mt-0.5 h-5 w-5 shrink-0 text-amber-600 dark:text-amber-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                        >
                            <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M12 3a9 9 0 100 18 9 9 0 000-18z"
                            />
                        </svg>

                        <div>
                            <p class="text-sm font-medium text-amber-800 dark:text-amber-300">
                                Store approval
                            </p>

                            <p class="mt-1 text-sm text-amber-700 dark:text-amber-400">
                                Your store will be created with pending status and will require approval before it becomes active.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4
                sm:flex-row sm:justify-end
                dark:border-slate-800 dark:bg-slate-950/50"
            >
                <a
                        href="{{ route('seller.store') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300
                    bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition
                    hover:bg-slate-50
                    dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200
                    dark:hover:bg-slate-800"
                >
                    Cancel
                </a>

                <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="save"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900
                    px-5 py-2.5 text-sm font-medium text-white transition
                    hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-60
                    dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100"
                >
                    <svg
                            wire:loading
                            wire:target="save"
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

                    <span wire:loading.remove wire:target="save">
                        Create Store
                    </span>

                    <span wire:loading wire:target="save">
                        Creating...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

