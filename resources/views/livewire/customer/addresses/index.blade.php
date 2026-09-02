<x-customer-layout title="Addresses">

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                    My Addresses
                </h1>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Manage your delivery addresses.
                </p>
            </div>

            <a
                    href="{{ route('customer.addresses.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200"
            >
                <svg
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                >
                    <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 4v16m8-8H4"
                    />
                </svg>

                Add Address
            </a>

        </div>

        {{-- Success Message --}}
        @if (session()->has('success'))

            <div
                    class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 dark:border-emerald-900/50 dark:bg-emerald-950/30 dark:text-emerald-300"
            >
                <svg
                        class="mt-0.5 h-5 w-5 shrink-0"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                >
                    <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M5 13l4 4L19 7"
                    />
                </svg>

                <p class="text-sm font-medium">
                    {{ session('success') }}
                </p>
            </div>

        @endif

        {{-- Empty State --}}
        @if ($this->addresses->isEmpty())

            <div
                    class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center dark:border-slate-700 dark:bg-slate-950"
            >

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800">

                    <svg
                            class="h-8 w-8 text-slate-500 dark:text-slate-400"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.7"
                    >
                        <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 21s8-4.5 8-10a8 8 0 10-16 0c0 5.5 8 10 8 10z"
                        />

                        <circle
                                cx="12"
                                cy="11"
                                r="2.5"
                        />
                    </svg>

                </div>

                <h2 class="mt-5 text-lg font-semibold text-slate-900 dark:text-white">
                    No addresses yet
                </h2>

                <p class="mx-auto mt-2 max-w-md text-sm text-slate-500 dark:text-slate-400">
                    Add your first delivery address to make checkout faster and easier.
                </p>

                <div class="mt-6">

                    <a
                            href="{{ route('customer.addresses.create') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200"
                    >
                        <svg
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                        >
                            <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 4v16m8-8H4"
                            />
                        </svg>

                        Add Your First Address
                    </a>

                </div>

            </div>

        @else

            {{-- Address Grid --}}
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                @foreach ($this->addresses as $address)

                    <div
                            wire:key="address-{{ $address->id }}"
                            class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md dark:border-slate-800 dark:bg-slate-950"
                    >

                        {{-- Top --}}
                        <div class="flex items-start justify-between gap-4 border-b border-slate-100 p-6 dark:border-slate-800">

                            <div class="flex min-w-0 items-start gap-4">

                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800">

                                    <svg
                                            class="h-5 w-5 text-slate-600 dark:text-slate-300"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                    >
                                        <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M12 21s8-4.5 8-10a8 8 0 10-16 0c0 5.5 8 10 8 10z"
                                        />

                                        <circle
                                                cx="12"
                                                cy="11"
                                                r="2.5"
                                        />
                                    </svg>

                                </div>

                                <div class="min-w-0">

                                    <div class="flex flex-wrap items-center gap-2">

                                        <h2 class="truncate text-base font-semibold text-slate-900 dark:text-white">
                                            {{ $address->label ?: 'Address' }}
                                        </h2>

                                        @if ($address->is_default)

                                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300">
                                                Default
                                            </span>

                                        @endif

                                    </div>

                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                        {{ $address->recipient_name }}
                                    </p>

                                </div>

                            </div>

                        </div>

                        {{-- Details --}}
                        <div class="space-y-3 p-6">

                            <div class="flex items-start gap-3">

                                <svg
                                        class="mt-0.5 h-5 w-5 shrink-0 text-slate-400"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                >
                                    <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3 5.5A2.5 2.5 0 015.5 3H7l2 4-2 2a13 13 0 006 6l2-2 4 2v1.5a2.5 2.5 0 01-2.5 2.5C9.044 19 5 14.956 5 10.5A2.5 2.5 0 013 8V5.5z"
                                    />
                                </svg>

                                <span class="text-sm text-slate-600 dark:text-slate-300">
                                    {{ $address->phone }}
                                </span>

                            </div>

                            <div class="flex items-start gap-3">

                                <svg
                                        class="mt-0.5 h-5 w-5 shrink-0 text-slate-400"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                >
                                    <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 21s8-4.5 8-10a8 8 0 10-16 0c0 5.5 8 10 8 10z"
                                    />

                                    <circle
                                            cx="12"
                                            cy="11"
                                            r="2.5"
                                    />
                                </svg>

                                <p class="text-sm leading-6 text-slate-600 dark:text-slate-300">
                                    {{ $address->address_line }}

                                    @if ($address->city)
                                        , {{ $address->city }}
                                    @endif

                                    @if ($address->country)
                                        , {{ $address->country }}
                                    @endif
                                </p>

                            </div>

                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-wrap items-center gap-2 border-t border-slate-100 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-900/40">

                            @if (! $address->is_default)

                                <button
                                        type="button"
                                        wire:click="setDefault({{ $address->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="setDefault({{ $address->id }})"
                                        class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
                                >
                                    <span wire:loading.remove wire:target="setDefault({{ $address->id }})">
                                        Set Default
                                    </span>

                                    <span wire:loading wire:target="setDefault({{ $address->id }})">
                                        Updating...
                                    </span>
                                </button>

                            @endif

                            <a
                                    href="{{ route('customer.addresses.edit', $address) }}"
                                    class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
                            >
                                Edit
                            </a>

                            <button
                                    type="button"
                                    wire:click="delete({{ $address->id }})"
                                    wire:confirm="Are you sure you want to delete this address?"
                                    wire:loading.attr="disabled"
                                    wire:target="delete({{ $address->id }})"
                                    class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-white px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-red-900/50 dark:bg-slate-900 dark:text-red-400 dark:hover:bg-red-950/30"
                            >
                                <span wire:loading.remove wire:target="delete({{ $address->id }})">
                                    Delete
                                </span>

                                <span wire:loading wire:target="delete({{ $address->id }})">
                                    Deleting...
                                </span>
                            </button>

                        </div>

                    </div>

                @endforeach

            </div>

        @endif

    </div>

</x-customer-layout>