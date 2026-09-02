<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        {{ $title ?? 'Dashboard' }} · Marketplace
    </title>

    {{-- Prevent dark-mode flash --}}
    <script>
        (() => {
            const theme = localStorage.getItem('theme');

            if (
                theme === 'dark' ||
                (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)
            ) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const dropdowns = document.querySelectorAll('header details');

        dropdowns.forEach((dropdown) => {
            dropdown.addEventListener('toggle', () => {
                if (!dropdown.open) return;

                dropdowns.forEach((otherDropdown) => {
                    if (otherDropdown !== dropdown) {
                        otherDropdown.removeAttribute('open');
                    }
                });
            });
        });
    });
</script>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased transition-colors duration-200 dark:bg-slate-950 dark:text-slate-100">

<div class="flex min-h-screen">

    {{-- =========================================================
        SIDEBAR
    ========================================================== --}}
    <aside class="hidden w-72 shrink-0 border-r border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900 lg:flex lg:flex-col">

        {{-- Logo --}}
        <div class="flex h-20 items-center border-b border-slate-100 px-6 dark:border-slate-800">

            <a href="{{ url('/customer') }}" class="flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-900 text-sm font-bold text-white shadow-sm dark:bg-white dark:text-slate-900">
                    M
                </div>

                <div>
                    <div class="text-lg font-bold tracking-tight text-slate-900 dark:text-white">
                        Marketplace
                    </div>

                    <div class="text-[11px] font-medium uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        Customer Portal
                    </div>
                </div>

            </a>

        </div>


        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-4 py-6">

            {{-- Main --}}
            <div class="mb-3 px-3 text-[11px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">
                Main
            </div>

            <div class="space-y-1">

                {{-- Dashboard --}}
                <a href="{{ url('/customer') }}"
                   class="group flex items-center gap-3 rounded-xl bg-slate-900 px-3.5 py-3 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100">

                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 dark:bg-slate-900/10">

                        <svg class="h-4.5 w-4.5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M3 12l9-9 9 9M5 10v10h14V10" />

                        </svg>

                    </span>

                    Dashboard

                </a>


                {{-- Orders --}}
                <a href="#"
                   class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium text-slate-600 transition-all duration-200 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white">

                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500 transition group-hover:bg-white dark:bg-slate-800 dark:text-slate-400 dark:group-hover:bg-slate-700">

                        <svg class="h-4.5 w-4.5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z" />

                        </svg>

                    </span>

                    <span class="flex-1">
                        My Orders
                    </span>

                </a>


                {{-- Cart --}}
                <a href="#"
                   class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium text-slate-600 transition-all duration-200 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white">

                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500 transition group-hover:bg-white dark:bg-slate-800 dark:text-slate-400 dark:group-hover:bg-slate-700">

                        <svg class="h-4.5 w-4.5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 4h13m-11 0a1 1 0 100 2 1 1 0 000-2zm8 0a1 1 0 100 2 1 1 0 000-2z" />

                        </svg>

                    </span>

                    <span class="flex-1">
                        My Cart
                    </span>

                    <span class="rounded-full bg-slate-900 px-2 py-0.5 text-[10px] font-bold text-white dark:bg-white dark:text-slate-900">
                        {{ auth()->user()->carts()->first()?->items()->count() ?? 0 }}
                    </span>

                </a>

            </div>


            {{-- Account --}}
            <div class="mb-3 mt-8 px-3 text-[11px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">
                Account
            </div>

            <div class="space-y-1">

                {{-- Profile --}}
                <a href="#"
                   class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white">

                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400">

                        <svg class="h-4.5 w-4.5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M15.5 7a3.5 3.5 0 11-7 0 3.5 3.5 0 017 0zM4 21a7.5 7.5 0 0115 0" />

                        </svg>

                    </span>

                    Profile

                </a>


                {{-- Addresses --}}
                <a href="#"
                   class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white">

                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400">

                        <svg class="h-4.5 w-4.5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M17.657 16.657L13.414 21a2 2 0 01-2.828 0l-4.243-4.343a8 8 0 1111.314 0z" />

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />

                        </svg>

                    </span>

                    Addresses

                </a>


                {{-- Wishlist --}}
                <a href="#"
                   class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white">

                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400">

                        <svg class="h-4.5 w-4.5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 000-7.78z" />

                        </svg>

                    </span>

                    Wishlist

                </a>

            </div>


            {{-- Settings --}}
            <div class="mb-3 mt-8 px-3 text-[11px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">
                Settings
            </div>

            <a href="#"
               class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white">

                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400">

                    <svg class="h-4.5 w-4.5"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="M12 15.5a3.5 3.5 0 100-7 3.5 3.5 0 000 7z" />

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="M19.4 15a1.7 1.7 0 00.34 1.88l.06.06-1.41 1.41-.06-.06a1.7 1.7 0 00-1.88-.34 1.7 1.7 0 00-1.02 1.55V20h-2v-.5a1.7 1.7 0 00-1.02-1.55 1.7 1.7 0 00-1.88.34l-.06.06-1.41-1.41.06-.06A1.7 1.7 0 007.6 15a1.7 1.7 0 00-1.55-1.02H5v-2h.5A1.7 1.7 0 007.05 11a1.7 1.7 0 00-.34-1.88l-.06-.06 1.41-1.41.06.06A1.7 1.7 0 0010 8.05 1.7 1.7 0 0011.02 6.5V6h2v.5A1.7 1.7 0 0014.04 8a1.7 1.7 0 001.88-.34l.06-.06 1.41 1.41-.06.06A1.7 1.7 0 0017 11a1.7 1.7 0 001.55 1.02H19v2h-.5A1.7 1.7 0 0017 15z" />

                    </svg>

                </span>

                Settings

            </a>

        </nav>


        {{-- User / Logout --}}
        <div class="border-t border-slate-100 p-4 dark:border-slate-800">

            <div class="rounded-2xl bg-slate-50 p-3 dark:bg-slate-800">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-900 text-sm font-bold text-white dark:bg-white dark:text-slate-900">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <div class="min-w-0 flex-1">

                        <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="truncate text-xs text-slate-500 dark:text-slate-400">
                            {{ auth()->user()->email }}
                        </p>

                    </div>

                </div>


                <form method="POST" action="{{ url('/logout') }}" class="mt-3">

                    @csrf

                    <button type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-red-600 transition hover:border-red-100 hover:bg-red-50 dark:border-slate-700 dark:bg-slate-900 dark:hover:border-red-900 dark:hover:bg-red-950/30">

                        <svg class="h-4 w-4"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1" />

                        </svg>

                        Sign out

                    </button>

                </form>

            </div>

        </div>

    </aside>


    {{-- =========================================================
        MAIN
    ========================================================== --}}
    <div class="flex min-w-0 flex-1 flex-col">


        {{-- =====================================================
            TOPBAR
        ====================================================== --}}
        <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur dark:border-slate-800 dark:bg-slate-900/90">

            <div class="flex h-20 items-center justify-between px-4 sm:px-6 lg:px-8">


                {{-- Mobile Menu --}}
                <details class="relative lg:hidden">

                    <summary
                            class="flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">

                        <svg class="h-5 w-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16" />

                        </svg>

                    </summary>

                    <div class="absolute left-0 top-14 z-50 w-72 rounded-2xl border border-slate-200 bg-white p-3 shadow-xl dark:border-slate-700 dark:bg-slate-900">

                        <a href="{{ url('/customer') }}"
                           class="flex items-center gap-3 rounded-xl bg-slate-900 px-3 py-3 text-sm font-semibold text-white dark:bg-white dark:text-slate-900">

                            Dashboard

                        </a>

                        <a href="#"
                           class="mt-1 flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">

                            My Orders

                        </a>

                        <a href="#"
                           class="mt-1 flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">

                            My Cart

                        </a>

                        <a href="#"
                           class="mt-1 flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">

                            Profile

                        </a>

                        <a href="#"
                           class="mt-1 flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">

                            Addresses

                        </a>

                    </div>

                </details>


                {{-- Page Title --}}
                <div class="ml-3">

                    <h1 class="text-base font-bold tracking-tight text-slate-900 dark:text-white sm:text-lg">
                        {{ $title ?? 'Dashboard' }}
                    </h1>

                    <p class="hidden text-xs text-slate-500 dark:text-slate-400 sm:block">
                        Manage your marketplace account
                    </p>

                </div>


                {{-- Right Actions --}}
                <div class="ml-auto flex items-center gap-2 sm:gap-3">


                    {{-- =================================================
                        THEME TOGGLE
                    ================================================== --}}
                    <button
                            type="button"
                            onclick="toggleTheme()"
                            class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800"
                            aria-label="Toggle dark mode"
                    >

                        {{-- Sun --}}
                        <svg id="sun-icon"
                             class="hidden h-5 w-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <circle cx="12"
                                    cy="12"
                                    r="4"
                                    stroke-width="1.8"/>

                            <path stroke-linecap="round"
                                  stroke-width="1.8"
                                  d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3l1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3l1.42-1.42"/>

                        </svg>


                        {{-- Moon --}}
                        <svg id="moon-icon"
                             class="h-5 w-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>

                        </svg>

                    </button>


                    {{-- =================================================
                        NOTIFICATIONS
                    ================================================== --}}
                    <details class="relative">

                        <summary
                                class="relative flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white">

                            <svg class="h-5 w-5"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="1.8"
                                      d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 01-6 0"/>

                            </svg>

                            <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-red-500 ring-2 ring-white dark:ring-slate-900"></span>

                        </summary>


                        <div class="absolute right-0 top-14 z-50 w-80 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-900">

                            <div class="flex items-center justify-between border-b border-slate-100 px-4 py-4 dark:border-slate-800">

                                <div>

                                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">
                                        Notifications
                                    </h3>

                                    <p class="text-xs text-slate-500 dark:text-slate-400">
                                        Stay up to date
                                    </p>

                                </div>

                                <span class="rounded-full bg-red-50 px-2 py-1 text-[10px] font-bold text-red-600 dark:bg-red-950/40 dark:text-red-400">
                                    New
                                </span>

                            </div>


                            <div class="divide-y divide-slate-100 dark:divide-slate-800">

                                <div class="flex gap-3 px-4 py-4 hover:bg-slate-50 dark:hover:bg-slate-800">

                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400">
                                        ✓
                                    </div>

                                    <div>

                                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                                            Welcome to Marketplace
                                        </p>

                                        <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                                            Your account is ready to use.
                                        </p>

                                        <p class="mt-1 text-[10px] text-slate-400">
                                            Just now
                                        </p>

                                    </div>

                                </div>


                                <div class="flex gap-3 px-4 py-4 hover:bg-slate-50 dark:hover:bg-slate-800">

                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400">
                                        i
                                    </div>

                                    <div>

                                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                                            Complete your profile
                                        </p>

                                        <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                                            Add your information to improve your experience.
                                        </p>

                                    </div>

                                </div>

                            </div>


                            <div class="border-t border-slate-100 p-3 dark:border-slate-800">

                                <a href="#"
                                   class="block rounded-xl bg-slate-50 px-3 py-2.5 text-center text-xs font-semibold text-slate-700 transition hover:bg-slate-100 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">

                                    View all notifications

                                </a>

                            </div>

                        </div>

                    </details>


                    {{-- =================================================
                        CART DROPDOWN
                    ================================================== --}}
                    @php
                        $cart = auth()->user()->carts()->with([
                            'items.product'
                        ])->first();

                        $cartItems = $cart?->items ?? collect();

                        $cartCount = $cartItems->sum('quantity');

                        $cartSubtotal = $cartItems->sum(function ($item) {
                            return $item->quantity * $item->unit_price;
                        });
                    @endphp

                    <details class="relative">

                        <summary
                                class="relative flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white">

                            <svg class="h-5 w-5"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="1.8"
                                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 4h13m-11 0a1 1 0 100 2 1 1 0 000-2zm8 0a1 1 0 100 2 1 1 0 000-2z"/>

                            </svg>


                            @if($cartCount > 0)

                                <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-slate-900 px-1 text-[9px] font-bold text-white ring-2 ring-white dark:bg-white dark:text-slate-900 dark:ring-slate-900">

                                    {{ $cartCount }}

                                </span>

                            @endif

                        </summary>


                        {{-- Cart Menu --}}
                        <div class="absolute right-0 top-14 z-50 w-[360px] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-900">

                            {{-- Header --}}
                            <div class="flex items-center justify-between border-b border-slate-100 px-4 py-4 dark:border-slate-800">

                                <div>

                                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">
                                        Shopping Cart
                                    </h3>

                                    <p class="text-xs text-slate-500 dark:text-slate-400">

                                        {{ $cartCount }}
                                        {{ $cartCount === 1 ? 'item' : 'items' }}

                                    </p>

                                </div>

                                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300">

                                    <svg class="h-4.5 w-4.5"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="1.8"
                                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 4h13"/>

                                    </svg>

                                </div>

                            </div>


                            {{-- Items --}}
                            @if($cartItems->isNotEmpty())

                                <div class="max-h-80 divide-y divide-slate-100 overflow-y-auto dark:divide-slate-800">

                                    @foreach($cartItems->take(5) as $item)

                                        <div class="flex gap-3 px-4 py-4 transition hover:bg-slate-50 dark:hover:bg-slate-800">

                                            {{-- Product Image --}}
                                            <div class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-slate-100 dark:bg-slate-800">

                                                @if($item->product?->image)

                                                    <img
                                                            src="{{ asset('storage/' . $item->product->image) }}"
                                                            alt="{{ $item->product->name }}"
                                                            class="h-full w-full object-cover"
                                                    >

                                                @else

                                                    <svg class="h-6 w-6 text-slate-400"
                                                         fill="none"
                                                         stroke="currentColor"
                                                         viewBox="0 0 24 24">

                                                        <path stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              stroke-width="1.5"
                                                              d="M4 16l4-4 4 4 4-5 4 5M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>

                                                    </svg>

                                                @endif

                                            </div>


                                            {{-- Product Info --}}
                                            <div class="min-w-0 flex-1">

                                                <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">

                                                    {{ $item->product?->name ?? 'Product' }}

                                                </p>

                                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">

                                                    Qty: {{ $item->quantity }}

                                                </p>

                                                <p class="mt-1 text-sm font-bold text-slate-900 dark:text-slate-100">

                                                    ${{ number_format($item->quantity * $item->unit_price, 2) }}

                                                </p>

                                            </div>

                                        </div>

                                    @endforeach

                                </div>

                            @else

                                {{-- Empty Cart --}}
                                <div class="px-6 py-10 text-center">

                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500">

                                        <svg class="h-7 w-7"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="1.5"
                                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 4h13"/>

                                        </svg>

                                    </div>

                                    <p class="mt-4 text-sm font-semibold text-slate-800 dark:text-slate-100">
                                        Your cart is empty
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                        Add products to your cart to see them here.
                                    </p>

                                </div>

                            @endif


                            {{-- Footer --}}
                            @if($cartItems->isNotEmpty())

                                <div class="border-t border-slate-100 p-4 dark:border-slate-800">

                                    <div class="mb-4 flex items-center justify-between">

                                        <span class="text-sm font-medium text-slate-500 dark:text-slate-400">
                                            Subtotal
                                        </span>

                                        <span class="text-base font-bold text-slate-900 dark:text-white">
                                            ${{ number_format($cartSubtotal, 2) }}
                                        </span>

                                    </div>


                                    <div class="grid grid-cols-2 gap-2">

                                        <a href="#"
                                           class="flex items-center justify-center rounded-xl border border-slate-200 px-3 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">

                                            View Cart

                                        </a>

                                        <a href="#"
                                           class="flex items-center justify-center rounded-xl bg-slate-900 px-3 py-2.5 text-xs font-bold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100">

                                            Checkout

                                        </a>

                                    </div>

                                </div>

                            @else

                                <div class="border-t border-slate-100 p-3 dark:border-slate-800">

                                    <a href="#"
                                       class="block rounded-xl bg-slate-900 px-3 py-2.5 text-center text-xs font-bold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100">

                                        Continue Shopping

                                    </a>

                                </div>

                            @endif

                        </div>

                    </details>


                    {{-- Divider --}}
                    <div class="hidden h-8 w-px bg-slate-200 dark:bg-slate-700 sm:block"></div>


                    {{-- =================================================
                        USER DROPDOWN
                    ================================================== --}}
                    <details class="relative">

                        <summary
                                class="flex cursor-pointer list-none items-center gap-2 rounded-xl p-1.5 transition hover:bg-slate-100 dark:hover:bg-slate-800">

                            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-900 text-sm font-bold text-white shadow-sm dark:bg-white dark:text-slate-900">

                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                            </div>

                            <div class="hidden text-left sm:block">

                                <p class="max-w-28 truncate text-xs font-bold text-slate-900 dark:text-white">
                                    {{ auth()->user()->name }}
                                </p>

                                <p class="text-[10px] text-slate-500 dark:text-slate-400">
                                    Customer
                                </p>

                            </div>

                            <svg class="hidden h-4 w-4 text-slate-400 sm:block"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M19 9l-7 7-7-7"/>

                            </svg>

                        </summary>


                        {{-- User Menu --}}
                        <div class="absolute right-0 top-14 z-50 w-56 overflow-hidden rounded-2xl border border-slate-200 bg-white p-2 shadow-2xl dark:border-slate-700 dark:bg-slate-900">

                            <div class="border-b border-slate-100 px-3 py-3 dark:border-slate-800">

                                <p class="truncate text-sm font-bold text-slate-900 dark:text-white">
                                    {{ auth()->user()->name }}
                                </p>

                                <p class="truncate text-xs text-slate-500 dark:text-slate-400">
                                    {{ auth()->user()->email }}
                                </p>

                            </div>


                            <a href="#"
                               class="mt-1 flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">

                                Profile

                            </a>

                            <a href="#"
                               class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">

                                Settings

                            </a>


                            <div class="my-1 border-t border-slate-100 dark:border-slate-800"></div>


                            <form method="POST" action="{{ url('/logout') }}">

                                @csrf

                                <button type="submit"
                                        class="flex w-full items-center rounded-xl px-3 py-2.5 text-left text-sm font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30">

                                    Sign out

                                </button>

                            </form>

                        </div>

                    </details>

                </div>

            </div>

        </header>


        {{-- =====================================================
            PAGE CONTENT
        ====================================================== --}}
        <main class="flex-1">

            <div class="mx-auto w-full max-w-7xl p-4 sm:p-6 lg:p-8">

                {{ $slot }}

            </div>

        </main>

    </div>

</div>


{{-- =============================================================
    DARK MODE SCRIPT
============================================================= --}}
<script>
    function updateThemeIcons() {
        const isDark = document.documentElement.classList.contains('dark');

        const sun = document.getElementById('sun-icon');
        const moon = document.getElementById('moon-icon');

        if (!sun || !moon) {
            return;
        }

        if (isDark) {
            sun.classList.remove('hidden');
            moon.classList.add('hidden');
        } else {
            sun.classList.add('hidden');
            moon.classList.remove('hidden');
        }
    }


    function toggleTheme() {
        const html = document.documentElement;

        html.classList.toggle('dark');

        const isDark = html.classList.contains('dark');

        localStorage.setItem(
            'theme',
            isDark ? 'dark' : 'light'
        );

        updateThemeIcons();
    }


    document.addEventListener('DOMContentLoaded', updateThemeIcons);
</script>


@livewireScripts

<script>
    document.addEventListener('livewire:init', () => {

        /*
        |--------------------------------------------------------------------------
        | Livewire Success Event
        |--------------------------------------------------------------------------
        */

        Livewire.on('show-success', (event) => {
            showSuccess(event.message);
        });


        /*
        |--------------------------------------------------------------------------
        | Livewire Error Event
        |--------------------------------------------------------------------------
        */

        Livewire.on('show-error', (event) => {
            showError(event.message);
        });


        /*
        |--------------------------------------------------------------------------
        | Session Flash Messages
        |--------------------------------------------------------------------------
        */

        @if (session('success'))
        showSuccess(@js(session('success')));
        @endif


        @if (session('error'))
        showError(@js(session('error')));
        @endif

    });
</script>

</body>
</html>

</body>
</html>
