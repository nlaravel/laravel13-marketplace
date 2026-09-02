<x-customer-layout title="Addresses">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                    My Addresses
                </h1>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Manage your delivery addresses.
                </p>
            </div>

            <a
                    {{--href="{{ route('customer.addresses.create') }}"--}}
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100"
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
                            d="M12 4v16m8-8H4"
                    />
                </svg>

                Add Address
            </a>
        </div>

        {{-- Success Message --}}
        @if (session()->has('success'))
            <div
                    class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-900/50 dark:bg-emerald-900/20 dark:text-emerald-400"
            >
                <svg
                        class="h-5 w-5 shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                >
                    <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                    />
                </svg>

                {{ session('success') }}
            </div>
        @endif

        {{-- Addresses --}}
        @if ($this->addresses->isEmpty())

            {{-- Empty State --}}
            <div
                    class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center dark:border-slate-700 dark:bg-slate-900"
            >
                <div
                        class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800"
                >
                    <svg
                            class="h-8 w-8 text-slate-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                    >
                        <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M17.657 16.657L13.414 21a2 2 0 01-2.828 0l-4.243-4.343a8 8 0 1111.314 0z"
                        />

                        <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>
                </div>

                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                    No addresses yet
                </h2>

                <p class="mx-auto mt-2 max-w-md text-sm text-slate-500 dark:text-slate-400">
                    Add your first delivery address to make checkout faster and easier.
                </p>

                <a
                        href="{{ route('customer.addresses.create') }}"
                        class="mt-6 inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900"
                >
                    Add Your First Address
                </a>
            </div>

        @else

            {{-- Address Cards --}}
            <div class="grid gap-5 md:grid-cols-2">

                @foreach ($this->addresses as $address)

                    <div
                            wire:key="address-{{ $address->id }}"
                            class="relative rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md dark:border-slate-800 dark:bg-slate-900"
                    >

                        {{-- Default Badge --}}
                        @if ($address->is_default)
                            <div class="absolute right-5 top-5">
                                <span
                                        class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400"
                                >
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Default
                                </span>
                            </div>
                        @endif

                        {{-- Header --}}
                        <div class="mb-5 flex items-start gap-4 pr-20">

                            <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800"
                            >
                                <svg
                                        class="h-5 w-5 text-slate-600 dark:text-slate-300"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                >
                                    <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.8"
                                            d="M17.657 16.657L13.414 21a2 2 0 01-2.828 0l-4.243-4.343a8 8 0 1111.314 0z"
                                    />

                                    <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.8"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                </svg>
                            </div>

                            <div>
                                <h3 class="font-semibold text-slate-900 dark:text-white">
                                    {{ $address->label ?: 'Address' }}
                                </h3>

                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                    {{ $address->recipient_name }}
                                </p>
                            </div>
                        </div>

                        {{-- Address Information --}}
                        <div class="space-y-2 text-sm text-slate-600 dark:text-slate-300">

                            @if ($address->phone)
                                <p class="font-medium">
                                    {{ $address->phone }}
                                </p>
                            @endif

                            @if ($address->address_line)
                                <p>
                                    {{ $address->address_line }}
                                </p>
                            @endif

                            @if ($address->street)
                                <p>
                                    {{ $address->street }}

                                    @if ($address->building)
                                        , Building {{ $address->building }}
                                    @endif

                                    @if ($address->apartment)
                                        , Apartment {{ $address->apartment }}
                                    @endif
                                </p>
                            @endif

                            @if ($address->area)
                                <p>
                                    {{ $address->area }}
                                </p>
                            @endif

                            @if ($address->city)
                                <p>
                                    {{ $address->city }}
                                </p>
                            @endif

                            @if ($address->country)
                                <p>
                                    {{ $address->country }}
                                </p>
                            @endif

                        </div>

                        {{-- Actions --}}
                        <div
                                class="mt-6 flex flex-wrap items-center gap-2 border-t border-slate-100 pt-5 dark:border-slate-800"
                        >

                            {{-- Set Default --}}
                            @if (! $address->is_default)

                                <button
                                        type="button"
                                        wire:click="setDefault({{ $address->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="setDefault({{ $address->id }})"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                                >
                                    <span wire:loading.remove wire:target="setDefault({{ $address->id }})">
                                        Set Default
                                    </span>

                                    <span wire:loading wire:target="setDefault({{ $address->id }})">
                                        Updating...
                                    </span>
                                </button>

                            @endif

                            {{-- Edit --}}
                            <a
                                    {{--href="{{ route('customer.addresses.edit', $address) }}"--}}
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
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
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h5m4-13h2a2 2 0 012 2v3m-4-5l-7 7-1 4 4-1 7-7m-3-3l3 3"
                                    />
                                </svg>

                                Edit
                            </a>

                            {{-- Delete --}}
                            <button
                                    type="button"
                                    wire:click="delete({{ $address->id }})"
                                    wire:confirm="Are you sure you want to delete this address?"
                                    wire:loading.attr="disabled"
                                    wire:target="delete({{ $address->id }})"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-red-900/50 dark:text-red-400 dark:hover:bg-red-900/20"
                            >
                                <span
                                        wire:loading.remove
                                        wire:target="delete({{ $address->id }})"
                                >
                                    Delete
                                </span>

                                <span
                                        wire:loading
                                        wire:target="delete({{ $address->id }})"
                                >
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