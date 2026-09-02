<x-customer-layout title="Add Address">

    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <div class="mb-2 flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                    <a
                            href="{{ route('customer.addresses.index') }}"
                            class="transition hover:text-slate-900 dark:hover:text-white"
                    >
                        Addresses
                    </a>

                    <span>/</span>

                    <span>Add Address</span>
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                    Add New Address
                </h1>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Add a new delivery address to your account.
                </p>
            </div>

        </div>

        {{-- Form --}}
        <form
                wire:submit="save"
                class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950"
        >

            <div class="p-6 sm:p-8">

                @include(
                    'livewire.customer.addresses._form-fields'
                )

            </div>

            {{-- Actions --}}
            <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-5 sm:flex-row sm:justify-end dark:border-slate-800 dark:bg-slate-900/50">

                <a
                        href="{{ route('customer.addresses.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
                >
                    Cancel
                </a>

                <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="save"
                        class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200"
                >
                    <span wire:loading.remove wire:target="save">
                        Create Address
                    </span>

                    <span
                            wire:loading
                            wire:target="save"
                            class="inline-flex items-center gap-2"
                    >
                        <svg
                                class="h-4 w-4 animate-spin"
                                viewBox="0 0 24 24"
                                fill="none"
                        >
                            <circle
                                    cx="12"
                                    cy="12"
                                    r="9"
                                    stroke="currentColor"
                                    stroke-width="3"
                                    class="opacity-25"
                            />

                            <path
                                    d="M21 12a9 9 0 0 0-9-9"
                                    stroke="currentColor"
                                    stroke-width="3"
                                    stroke-linecap="round"
                            />
                        </svg>

                        Saving...
                    </span>
                </button>

            </div>

        </form>

    </div>

</x-customer-layout>