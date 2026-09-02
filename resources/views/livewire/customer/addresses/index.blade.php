<div class="min-h-full">

    {{-- Header --}}
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">
                        Addresses
                    </h1>

                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Manage your delivery addresses
                    </p>
                </div>
            </div>
        </div>

        <a
                href="{{ route('customer.addresses.create') }}"
                wire:navigate
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4"/>
            </svg>

            <span>Add Address</span>
        </a>
    </div>




    {{-- Addresses --}}
    @if ($this->addresses->isNotEmpty())

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">

            @foreach ($this->addresses as $address)

                <div
                        wire:key="address-{{ $address->id }}"
                        class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800"
                >

                    {{-- Card Header --}}
                    <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-700">

                        <div class="flex items-start justify-between gap-3">

                            <div class="flex min-w-0 items-center gap-3">

                                {{-- Home Icon --}}
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-300">

                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                              d="M3 10.5L12 3l9 7.5"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                              d="M5.5 9.5V21h13V9.5M9 21v-6h6v6"/>
                                    </svg>

                                </div>

                                <div class="min-w-0">

                                    <div class="flex flex-wrap items-center gap-2">

                                        <h2 class="truncate text-base font-semibold text-slate-900 dark:text-white">
                                            {{ $address->label ?: 'Address' }}
                                        </h2>

                                        @if ($address->is_default)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">

                                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l3-3z"
                                                          clip-rule="evenodd"/>
                                                </svg>

                                                Default
                                            </span>
                                        @endif

                                    </div>

                                    <p class="mt-1 truncate text-sm text-slate-500 dark:text-slate-400">
                                        {{ $address->recipient_name }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Card Body --}}
                    <div class="flex-1 space-y-4 px-5 py-5">

                        {{-- Recipient --}}
                        <div class="flex items-start gap-3">

                            <div class="mt-0.5 text-slate-400 dark:text-slate-500">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M4 21a8 8 0 0116 0"/>
                                </svg>
                            </div>

                            <div class="min-w-0">
                                <p class="text-xs font-medium text-slate-400 dark:text-slate-500">
                                    Recipient
                                </p>

                                <p class="mt-1 truncate text-sm font-medium text-slate-700 dark:text-slate-200">
                                    {{ $address->recipient_name }}
                                </p>
                            </div>

                        </div>


                        {{-- Phone --}}
                        <div class="flex items-start gap-3">

                            <div class="mt-0.5 text-slate-400 dark:text-slate-500">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M3 5a2 2 0 012-2h2.28a2 2 0 011.94 1.515L10 7.5a2 2 0 01-.45 1.86l-1.27 1.27a16 16 0 006.09 6.09l1.27-1.27a2 2 0 011.86-.45l2.985.78A2 2 0 0122 17.72V20a2 2 0 01-2 2h-1C9.611 22 2 14.389 2 5V5a2 2 0 011-0z"/>
                                </svg>
                            </div>

                            <div class="min-w-0">
                                <p class="text-xs font-medium text-slate-400 dark:text-slate-500">
                                    Phone
                                </p>

                                <p class="mt-1 truncate text-sm font-medium text-slate-700 dark:text-slate-200">
                                    {{ $address->phone }}
                                </p>
                            </div>

                        </div>


                        {{-- Location --}}
                        <div class="flex items-start gap-3">

                            <div class="mt-0.5 text-slate-400 dark:text-slate-500">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M12 21s7-5.2 7-11a7 7 0 10-14 0c0 5.8 7 11 7 11z"/>
                                    <circle cx="12" cy="10" r="2.5" stroke-width="1.8"/>
                                </svg>
                            </div>

                            <div class="min-w-0">

                                <p class="text-xs font-medium text-slate-400 dark:text-slate-500">
                                    Location
                                </p>

                                <p class="mt-1 truncate text-sm font-medium text-slate-700 dark:text-slate-200">
                                    {{ $address->country }} · {{ $address->city }}
                                </p>

                                @if ($address->area)
                                    <p class="mt-0.5 truncate text-xs text-slate-500 dark:text-slate-400">
                                        {{ $address->area }}
                                    </p>
                                @endif

                            </div>

                        </div>


                        {{-- Divider --}}
                        <div class="border-t border-slate-100 dark:border-slate-700"></div>


                        {{-- Street --}}
                        <div class="flex items-start gap-3">

                            <div class="mt-0.5 text-slate-400 dark:text-slate-500">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M4 19h16M6 17V7l6-4 6 4v10"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M9 17v-5h6v5"/>
                                </svg>
                            </div>

                            <div class="min-w-0">

                                <p class="text-xs font-medium text-slate-400 dark:text-slate-500">
                                    Street
                                </p>

                                <p class="mt-1 truncate text-sm font-medium text-slate-700 dark:text-slate-200">
                                    {{ $address->street }}
                                </p>

                            </div>

                        </div>


                        {{-- Building & Apartment --}}
                        <div class="grid grid-cols-2 gap-4">

                            {{-- Building --}}
                            <div class="flex items-start gap-3">

                                <div class="mt-0.5 text-slate-400 dark:text-slate-500">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                              d="M4 21V5a2 2 0 012-2h12a2 2 0 012 2v16M4 21h16"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                              d="M8 7h2m4 0h2M8 11h2m4 0h2M8 15h2m4 0h2"/>
                                    </svg>
                                </div>

                                <div class="min-w-0">

                                    <p class="text-xs font-medium text-slate-400 dark:text-slate-500">
                                        Building
                                    </p>

                                    <p class="mt-1 truncate text-sm font-medium text-slate-700 dark:text-slate-200">
                                        {{ $address->building ?: '—' }}
                                    </p>

                                </div>

                            </div>


                            {{-- Apartment --}}
                            <div class="flex items-start gap-3">

                                <div class="mt-0.5 text-slate-400 dark:text-slate-500">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                              d="M4 21h16M5 21V7a2 2 0 012-2h10a2 2 0 012 2v14"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                              d="M9 9h6M9 13h6M9 17h2m2 0h2"/>
                                    </svg>
                                </div>

                                <div class="min-w-0">

                                    <p class="text-xs font-medium text-slate-400 dark:text-slate-500">
                                        Apartment
                                    </p>

                                    <p class="mt-1 truncate text-sm font-medium text-slate-700 dark:text-slate-200">
                                        {{ $address->apartment ?: '—' }}
                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- Full Address --}}
                        <div class="rounded-xl bg-slate-50 p-3.5 dark:bg-slate-700/40">

                            <div class="flex items-start gap-3">

                                <div class="mt-0.5 text-indigo-500 dark:text-indigo-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                              d="M9 20l-5-2V6l5 2m0 12l6-2m-6 2V8m6 10l5 2V6l-5-2m0 14V6m0 0L9 8"/>
                                    </svg>
                                </div>

                                <div class="min-w-0">

                                    <p class="text-xs font-medium text-slate-400 dark:text-slate-500">
                                        Address
                                    </p>

                                    <p class="mt-1 text-sm leading-5 text-slate-600 dark:text-slate-300">
                                        {{ $address->address_line }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Card Footer --}}
                    <div class="border-t border-slate-100 bg-slate-50/70 px-5 py-4 dark:border-slate-700 dark:bg-slate-800/70">

                        <div class="flex items-center gap-2">

                            {{-- Set Default --}}
                            @if (!$address->is_default)

                                <button
                                        type="button"
                                        wire:click="setDefault({{ $address->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="setDefault({{ $address->id }})"
                                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-600 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 dark:hover:border-emerald-800 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-400"
                                >

                                    <svg
                                            wire:loading.remove
                                            wire:target="setDefault({{ $address->id }})"
                                            class="h-4 w-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                              d="M12 3l2.78 5.63 6.22.9-4.5 4.38 1.06 6.19L12 17.17l-5.56 2.93 1.06-6.19L3 9.53l6.22-.9L12 3z"/>
                                    </svg>

                                    <svg
                                            wire:loading
                                            wire:target="setDefault({{ $address->id }})"
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
                                    href="{{ route('customer.addresses.edit', $address) }}"
                                    wire:navigate
                                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-600 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 dark:hover:border-indigo-800 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-400"
                            >

                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M16.862 4.487l1.687-1.687a2.121 2.121 0 013 3l-9.9 9.9a2 2 0 01-.878.497l-3.2.8.8-3.2a2 2 0 01.497-.878l7.994-7.994z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M15 5l3 3"/>
                                </svg>

                                <span>Edit</span>

                            </a>


                            {{-- Delete --}}
                            <button
                                    type="button"
                                    onclick="confirmDeleteAddress({{ $address->id }}, this)"
                                    wire:loading.attr="disabled"
                                    wire:target="delete"
                                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-600 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 dark:hover:border-rose-800 dark:hover:bg-rose-500/10 dark:hover:text-rose-400"
                            >
                                <svg
                                        wire:loading.remove
                                        wire:target="delete"
                                        class="h-4 w-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                >
                                    <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.8"
                                            d="M6 7h12M9 7V4h6v3m-8 0l1 13h8l1-13M10 11v5m4-5v5"
                                    />
                                </svg>

                                <svg
                                        wire:loading
                                        wire:target="delete"
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

                                <span wire:loading.remove wire:target="delete">
        Delete
    </span>

                                <span wire:loading wire:target="delete">
        Deleting...
    </span>
                            </button>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        {{-- Empty State --}}
        <div class="flex min-h-[420px] flex-col items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-white px-6 text-center dark:border-slate-700 dark:bg-slate-800">

            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-slate-700 dark:text-slate-500">

                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                          d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>

            </div>

            <h2 class="mt-5 text-lg font-semibold text-slate-900 dark:text-white">
                No addresses yet
            </h2>

            <p class="mt-2 max-w-md text-sm leading-6 text-slate-500 dark:text-slate-400">
                Add your first delivery address to make checkout faster and easier.
            </p>

            <a
                    href="{{ route('customer.addresses.create') }}"
                    wire:navigate
                    class="mt-6 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4v16m8-8H4"/>
                </svg>

                <span>Add Address</span>
            </a>

        </div>

    @endif

</div>

@script
<script>
    window.confirmDeleteAddress = async function (addressId, button) {
        const confirmed = await confirmDelete(
            'This address will be permanently deleted.'
        );

        if (!confirmed) {
            return;
        }

        const component = Livewire.find(
            button.closest('[wire\\:id]').getAttribute('wire:id')
        );

        if (!component) {
            console.error('Livewire component not found.');
            return;
        }

        component.delete(addressId);
    };
</script>
@endscript