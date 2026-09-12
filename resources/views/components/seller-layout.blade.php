@php
    $user = auth()->user();
@endphp

<!DOCTYPE html>
<html
        lang="{{ str_replace('_', '-', app()->getLocale()) }}"
        dir="ltr"
>
<head>
    <meta charset="utf-8">

    <meta
            name="viewport"
            content="width=device-width, initial-scale=1"
    >

    <title>
        {{ $title ?? 'Seller Dashboard' }} · Marketplace
    </title>

    {{-- Prevent dark-mode flash --}}
    <script>
        (() => {
            const theme = localStorage.getItem('theme');

            if (
                theme === 'dark' ||
                (
                    !theme &&
                    window.matchMedia('(prefers-color-scheme: dark)').matches
                )
            ) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="min-h-screen bg-slate-50 text-slate-900 antialiased transition-colors duration-200 dark:bg-slate-950 dark:text-slate-100">

<div class="flex min-h-screen">

    {{-- Desktop Sidebar --}}
    <aside class="hidden w-72 flex-col border-r border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900 lg:flex">

        {{-- Logo --}}
        <div class="flex h-20 items-center border-b border-slate-200 px-6 dark:border-slate-800">
            <a
                    href="{{ route('seller.dashboard') }}"
                    class="flex items-center gap-3"
            >
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-900 text-lg font-bold text-white dark:bg-white dark:text-slate-900">
                    M
                </div>

                <div>
                    <div class="font-semibold text-slate-900 dark:text-white">
                        Marketplace
                    </div>

                    <div class="text-xs text-slate-500 dark:text-slate-400">
                        Seller Portal
                    </div>
                </div>
            </a>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 space-y-7 overflow-y-auto p-4">

            {{-- Main --}}
            <div>
                <div class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Main
                </div>

                <a
                        href="{{ route('seller.dashboard') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                        {{ request()->is('seller') || request()->is('seller/')
                            ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900'
                            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white' }}"
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
                                stroke-width="1.8"
                                d="M3 12l9-9 9 9M5 10v10h14V10M9 20v-6h6v6"
                        />
                    </svg>

                    <span>Dashboard</span>
                </a>
            </div>

            {{-- Store --}}
            <div>
                <div class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Store
                </div>

                <div class="space-y-1">

                    <div class="flex cursor-not-allowed items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-400 dark:text-slate-600">
                        <svg
                                class="h-5 w-5 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                        >
                            <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M3 10h18M5 10v10h14V10M7 10V6a5 5 0 0110 0v4"
                            />
                        </svg>

                        <span>My Store</span>
                    </div>

                    <div class="flex cursor-not-allowed items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-400 dark:text-slate-600">
                        <svg
                                class="h-5 w-5 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                        >
                            <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M10.5 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4.5M14 4h6m0 0v6m0-6L11 13"
                            />
                        </svg>

                        <span>Store Settings</span>
                    </div>

                </div>
            </div>

            {{-- Products --}}
            <div>
                <div class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Products
                </div>

                <div class="space-y-1">

                    <div class="flex cursor-not-allowed items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-400 dark:text-slate-600">
                        <svg
                                class="h-5 w-5 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                        >
                            <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M20 7l-8-4-8 4m16 0v10l-8 4-8-4V7m16 0l-8 4m-8-4l8 4m0 0v10"
                            />
                        </svg>

                        <span>All Products</span>
                    </div>

                    <div class="flex cursor-not-allowed items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-400 dark:text-slate-600">
                        <svg
                                class="h-5 w-5 shrink-0"
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

                        <span>Add Product</span>
                    </div>

                </div>
            </div>

            {{-- Operations --}}
            <div>
                <div class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Operations
                </div>

                <div class="space-y-1">

                    <div class="flex cursor-not-allowed items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-400 dark:text-slate-600">
                        <svg
                                class="h-5 w-5 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                        >
                            <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M4 7h16M4 12h16M4 17h16"
                            />
                        </svg>

                        <span>Inventory</span>
                    </div>

                    <div class="flex cursor-not-allowed items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-400 dark:text-slate-600">
                        <svg
                                class="h-5 w-5 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                        >
                            <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M3 7h18M6 7v10m12-10v10M4 17h16M8 11h8"
                            />
                        </svg>

                        <span>Orders</span>
                    </div>

                    <div class="flex cursor-not-allowed items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-400 dark:text-slate-600">
                        <svg
                                class="h-5 w-5 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                        >
                            <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M3 3v18h18M7 16l4-5 3 3 5-7"
                            />
                        </svg>

                        <span>Sales &amp; Analytics</span>
                    </div>

                    <div class="flex cursor-not-allowed items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-400 dark:text-slate-600">
                        <svg
                                class="h-5 w-5 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                        >
                            <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M15 17h5l-1.5-2.5V11a6.5 6.5 0 00-13 0v3.5L4 17h5m6 0a3 3 0 01-6 0"
                            />
                        </svg>

                        <span>Notifications</span>
                    </div>

                </div>
            </div>

            {{-- Settings --}}
            <div>
                <div class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Settings
                </div>

                <div class="flex cursor-not-allowed items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-400 dark:text-slate-600">
                    <svg
                            class="h-5 w-5 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                    >
                        <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M10.5 3h3l.7 2.2a7.5 7.5 0 012 1.2l2.2-.7 1.5 2.6-1.5 1.7a7.5 7.5 0 010 2.4l1.5 1.7-1.5 2.6-2.2-.7a7.5 7.5 0 01-2 1.2l-.7 2.2h-3l-.7-2.2a7.5 7.5 0 01-2-1.2l-2.2.7-1.5-2.6 1.5-1.7a7.5 7.5 0 010-2.4L4.1 8.3l1.5-2.6 2.2.7a7.5 7.5 0 012-1.2L10.5 3z"
                        />
                        <circle
                                cx="12"
                                cy="11.2"
                                r="2.5"
                        />
                    </svg>

                    <span>Settings</span>
                </div>
            </div>

        </nav>

        {{-- User / Logout --}}
        <div class="border-t border-slate-200 p-4 dark:border-slate-800">

            <div class="mb-3 flex items-center gap-3 px-2">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-200 text-sm font-semibold text-slate-700 dark:bg-slate-700 dark:text-slate-200">
                    {{ strtoupper(substr($user?->name ?? 'S', 0, 1)) }}
                </div>

                <div class="min-w-0">
                    <div class="truncate text-sm font-semibold text-slate-900 dark:text-white">
                        {{ $user?->name ?? 'Seller' }}
                    </div>

                    <div class="truncate text-xs text-slate-500 dark:text-slate-400">
                        Seller
                    </div>
                </div>

            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                        type="submit"
                        class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-red-50 hover:text-red-600 dark:text-slate-300 dark:hover:bg-red-950/30 dark:hover:text-red-400"
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
                                stroke-width="1.8"
                                d="M15 12H3m0 0l4-4m-4 4l4 4M21 5v14a2 2 0 01-2 2h-6"
                        />
                    </svg>

                    <span>Logout</span>
                </button>
            </form>

        </div>

    </aside>

    {{-- Main Area --}}
    <div class="flex min-w-0 flex-1 flex-col">

        {{-- Topbar --}}
        <header class="sticky top-0 z-30 flex h-20 items-center justify-between border-b border-slate-200 bg-white/95 px-4 backdrop-blur sm:px-6 dark:border-slate-800 dark:bg-slate-900/95">

            <div class="flex items-center gap-3">

                {{-- Mobile Menu --}}
                <details class="relative lg:hidden">
                    <summary class="flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-xl text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">
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
                                    d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>
                    </summary>

                    <div class="absolute left-0 top-12 z-50 w-72 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl dark:border-slate-700 dark:bg-slate-900">

                        <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                            <div class="font-semibold text-slate-900 dark:text-white">
                                Seller Portal
                            </div>

                            <div class="text-xs text-slate-500 dark:text-slate-400">
                                Marketplace
                            </div>
                        </div>

                        <nav class="space-y-1 p-3">

                            <a
                                    href="{{ route('seller.dashboard') }}"
                                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium
                                    {{ request()->is('seller') || request()->is('seller/')
                                        ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900'
                                        : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}"
                            >
                                <span>Dashboard</span>
                            </a>

                            <div class="mt-3 border-t border-slate-200 pt-3 dark:border-slate-800">

                                <div class="space-y-1 text-sm">

                                    <div class="cursor-not-allowed rounded-xl px-3 py-2.5 text-slate-400 dark:text-slate-600">
                                        My Store
                                    </div>

                                    <div class="cursor-not-allowed rounded-xl px-3 py-2.5 text-slate-400 dark:text-slate-600">
                                        Products
                                    </div>

                                    <div class="cursor-not-allowed rounded-xl px-3 py-2.5 text-slate-400 dark:text-slate-600">
                                        Inventory
                                    </div>

                                    <div class="cursor-not-allowed rounded-xl px-3 py-2.5 text-slate-400 dark:text-slate-600">
                                        Orders
                                    </div>

                                    <div class="cursor-not-allowed rounded-xl px-3 py-2.5 text-slate-400 dark:text-slate-600">
                                        Sales &amp; Analytics
                                    </div>

                                    <div class="cursor-not-allowed rounded-xl px-3 py-2.5 text-slate-400 dark:text-slate-600">
                                        Notifications
                                    </div>

                                    <div class="cursor-not-allowed rounded-xl px-3 py-2.5 text-slate-400 dark:text-slate-600">
                                        Settings
                                    </div>

                                </div>

                            </div>

                        </nav>

                    </div>
                </details>

                <div>
                    <div class="text-lg font-semibold text-slate-900 dark:text-white">
                        {{ $title ?? 'Dashboard' }}
                    </div>

                    <div class="hidden text-sm text-slate-500 sm:block dark:text-slate-400">
                        Seller Portal
                    </div>
                </div>

            </div>

            <div class="flex items-center gap-2">

                {{-- Theme Toggle --}}
                <button
                        type="button"
                        onclick="toggleTheme()"
                        class="flex h-10 w-10 items-center justify-center rounded-xl text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800"
                        aria-label="Toggle theme"
                >
                    <svg
                            id="theme-icon-sun"
                            class="hidden h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                    >
                        <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M12 3v2m0 14v2M5.6 5.6l1.4 1.4m10 10l1.4 1.4M3 12h2m14 0h2M5.6 18.4L7 17m10-10l1.4-1.4M16 12a4 4 0 11-8 0 4 4 0 018 0z"
                        />
                    </svg>

                    <svg
                            id="theme-icon-moon"
                            class="hidden h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                    >
                        <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M21 12.8A8.5 8.5 0 1111.2 3 6.7 6.7 0 0021 12.8z"
                        />
                    </svg>
                </button>

                {{-- User Dropdown --}}
                <details class="relative">
                    <summary class="flex cursor-pointer list-none items-center gap-2 rounded-xl px-2 py-1.5 hover:bg-slate-100 dark:hover:bg-slate-800">

                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-200 text-sm font-semibold text-slate-700 dark:bg-slate-700 dark:text-slate-200">
                            {{ strtoupper(substr($user?->name ?? 'S', 0, 1)) }}
                        </div>

                        <span class="hidden max-w-32 truncate text-sm font-medium text-slate-700 sm:block dark:text-slate-200">
                            {{ $user?->name ?? 'Seller' }}
                        </span>

                        <svg
                                class="hidden h-4 w-4 text-slate-400 sm:block"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                        >
                            <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M6 9l6 6 6-6"
                            />
                        </svg>

                    </summary>

                    <div class="absolute right-0 top-12 z-50 w-56 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl dark:border-slate-700 dark:bg-slate-900">

                        <div class="border-b border-slate-200 px-4 py-3 dark:border-slate-800">
                            <div class="truncate text-sm font-semibold text-slate-900 dark:text-white">
                                {{ $user?->name ?? 'Seller' }}
                            </div>

                            <div class="truncate text-xs text-slate-500 dark:text-slate-400">
                                {{ $user?->email ?? '' }}
                            </div>
                        </div>

                        <div class="p-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <button
                                        type="submit"
                                        class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-red-50 hover:text-red-600 dark:text-slate-300 dark:hover:bg-red-950/30 dark:hover:text-red-400"
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
                                                stroke-width="1.8"
                                                d="M15 12H3m0 0l4-4m-4 4l4 4M21 5v14a2 2 0 01-2 2h-6"
                                        />
                                    </svg>

                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>

                    </div>
                </details>

            </div>

        </header>

        {{-- Content --}}
        <main class="flex-1">
            <div class="mx-auto w-full max-w-7xl p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </div>
        </main>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const dropdowns = document.querySelectorAll('header details');

        dropdowns.forEach((dropdown) => {
            dropdown.addEventListener('toggle', () => {
                if (!dropdown.open) {
                    return;
                }

                dropdowns.forEach((otherDropdown) => {
                    if (otherDropdown !== dropdown) {
                        otherDropdown.removeAttribute('open');
                    }
                });
            });
        });
    });

    function updateThemeIcons() {
        const isDark = document.documentElement.classList.contains('dark');

        const sunIcon = document.getElementById('theme-icon-sun');
        const moonIcon = document.getElementById('theme-icon-moon');

        if (!sunIcon || !moonIcon) {
            return;
        }

        sunIcon.classList.toggle('hidden', !isDark);
        moonIcon.classList.toggle('hidden', isDark);
    }

    function toggleTheme() {
        const isDark = document.documentElement.classList.toggle('dark');

        localStorage.setItem(
            'theme',
            isDark ? 'dark' : 'light'
        );

        updateThemeIcons();
    }

    document.addEventListener('DOMContentLoaded', updateThemeIcons);
</script>

@livewireScripts

</body>
</html>

