<div>
    {{-- Header --}}
    <div class="mb-8">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="mb-2 flex items-center gap-2 text-sm font-medium text-slate-500 dark:text-slate-400">
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
                                d="M3 10h18M5 10v9h14v-9M7 10V7l5-4 5 4v3"
                        />
                    </svg>

                    Store Management
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                    My Store
                </h1>

                <p class="mt-1.5 max-w-2xl text-sm leading-6 text-slate-500 dark:text-slate-400">
                    Manage your stores, monitor their status, and access store settings.
                </p>
            </div>

            <a
                    href="{{ route('seller.store.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900
    px-4 py-2.5 text-sm font-medium text-white transition
    hover:bg-slate-800
    dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100"
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

                Add Store
            </a>
        </div>
    </div>

    {{-- Stores --}}
    @if ($this->stores->isEmpty())
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex min-h-[360px] flex-col items-center justify-center px-6 py-16 text-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500">
                    <svg
                            class="h-8 w-8"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                    >
                        <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M3 10h18M5 10v9h14v-9M7 10V7l5-4 5 4v3M9 19v-5h6v5"
                        />
                    </svg>
                </div>

                <h2 class="mt-5 text-lg font-semibold text-slate-900 dark:text-white">
                    No stores yet
                </h2>

                <p class="mt-2 max-w-md text-sm leading-6 text-slate-500 dark:text-slate-400">
                    You haven't created a store yet. Once store creation is available,
                    you will be able to set up your first store here.
                </p>

                <button
                        type="button"
                        disabled
                        class="mt-6 cursor-not-allowed rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-400 dark:bg-slate-800 dark:text-slate-600"
                >
                    Create your first store
                </button>
            </div>
        </div>
    @else
        {{-- Store Summary --}}
        <div class="mb-5 grid gap-4 sm:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300">
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
                                    d="M3 10h18M5 10v9h14v-9M7 10V7l5-4 5 4v3"
                            />
                        </svg>
                    </div>

                    <div>
                        <div class="text-xs font-medium uppercase tracking-wide text-slate-400 dark:text-slate-500">
                            Total stores
                        </div>

                        <div class="mt-0.5 text-xl font-bold text-slate-900 dark:text-white">
                            {{ $this->stores->count() }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
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
                                    d="M5 13l4 4L19 7"
                            />
                        </svg>
                    </div>

                    <div>
                        <div class="text-xs font-medium uppercase tracking-wide text-slate-400 dark:text-slate-500">
                            Active stores
                        </div>

                        <div class="mt-0.5 text-xl font-bold text-slate-900 dark:text-white">
                            {{ $this->stores->where('status.value', 'approved')->count() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Store Cards --}}
        <div class="grid gap-5 xl:grid-cols-2">
            @foreach ($this->stores as $store)
                @php
                    $status = $store->status->value;

                    $statusStyles = match ($status) {
                        'approved' => [
                            'badge' => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-400/20',
                            'dot' => 'bg-emerald-500',
                        ],
                        'pending' => [
                            'badge' => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20 dark:bg-amber-500/10 dark:text-amber-400 dark:ring-amber-400/20',
                            'dot' => 'bg-amber-500',
                        ],
                        'rejected' => [
                            'badge' => 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-400/20',
                            'dot' => 'bg-red-500',
                        ],
                        default => [
                            'badge' => 'bg-slate-100 text-slate-600 ring-1 ring-inset ring-slate-500/10 dark:bg-slate-800 dark:text-slate-400 dark:ring-slate-400/10',
                            'dot' => 'bg-slate-400',
                        ],
                    };
                @endphp

                <article
                        wire:key="store-{{ $store->id }}"
                        class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900"
                >
                    {{-- Top Accent --}}
                    <div
                            class="absolute inset-x-0 top-0 h-1
                        {{ $status === 'approved'
                            ? 'bg-emerald-500'
                            : ($status === 'pending'
                                ? 'bg-amber-500'
                                : ($status === 'rejected' ? 'bg-red-500' : 'bg-slate-400')) }}"
                    ></div>

                    <div class="p-6">
                        {{-- Card Header --}}
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex min-w-0 items-start gap-4">
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
                                                d="M3 10h18M5 10v9h14v-9M7 10V7l5-4 5 4v3M9 19v-5h6v5"
                                        />
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <h2 class="truncate text-lg font-bold text-slate-900 dark:text-white">
                                        {{ $store->name }}
                                    </h2>

                                    <div class="mt-1 flex min-w-0 items-center gap-1.5 text-sm text-slate-500 dark:text-slate-400">
                                        <span class="text-slate-400 dark:text-slate-500">/</span>

                                        <span class="truncate">
                                            {{ $store->slug }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Status --}}
                            <span
                                    class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-semibold {{ $statusStyles['badge'] }}"
                            >
                                <span class="h-1.5 w-1.5 rounded-full {{ $statusStyles['dot'] }}"></span>

                                {{ ucfirst($status) }}
                            </span>
                        </div>

                        {{-- Description --}}
                        <div class="mt-6">
                            @if ($store->description)
                                <p class="line-clamp-3 text-sm leading-6 text-slate-600 dark:text-slate-300">
                                    {{ $store->description }}
                                </p>
                            @else
                                <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800/60">
                                    <p class="text-sm italic text-slate-400 dark:text-slate-500">
                                        No description provided.
                                    </p>
                                </div>
                            @endif
                        </div>

                        {{-- Metadata --}}
                        <div class="mt-6 grid grid-cols-2 gap-3">
                            <div class="rounded-xl bg-slate-50 p-3.5 dark:bg-slate-800/60">
                                <div class="text-xs font-medium text-slate-400 dark:text-slate-500">
                                    Status
                                </div>

                                <div class="mt-1 text-sm font-semibold capitalize text-slate-700 dark:text-slate-200">
                                    {{ $status }}
                                </div>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-3.5 dark:bg-slate-800/60">
                                <div class="text-xs font-medium text-slate-400 dark:text-slate-500">
                                    Created
                                </div>

                                <div class="mt-1 text-sm font-semibold text-slate-700 dark:text-slate-200">
                                    {{ $store->created_at?->format('M d, Y') }}
                                </div>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-5 dark:border-slate-800">
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
                                            d="M8 7V3m8 4V3M4 9h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"
                                    />
                                </svg>

                                Store #{{ $store->id }}
                            </div>

                            <a
                                    href="{{ route('seller.store.edit', $store->id) }}"
                                    class="inline-flex items-center justify-center rounded-lg border border-slate-300
    px-3 py-2 text-sm font-medium text-slate-700 transition
    hover:bg-slate-50
    dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-700"
                            >
                                Manage
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>

