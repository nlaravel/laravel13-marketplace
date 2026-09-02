<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        {{ $title ?? 'Customer Dashboard' }} - Marketplace
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="min-h-screen bg-gray-50 text-gray-900">

<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="hidden w-64 shrink-0 border-r border-gray-200 bg-white lg:block">
        <div class="flex h-16 items-center border-b border-gray-200 px-6">
            <a href="{{ url('/customer') }}"
               class="text-xl font-bold">
                Marketplace
            </a>
        </div>

        <nav class="p-4">
            <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">
                Main
            </p>

            <a href="{{ url('/customer') }}"
               class="mb-1 flex items-center rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-gray-100">
                Dashboard
            </a>

            <a href="#"
               class="mb-1 flex items-center rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-gray-100">
                My Orders
            </a>

            <a href="#"
               class="mb-1 flex items-center rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-gray-100">
                My Cart
            </a>

            <p class="mb-3 mt-7 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">
                Account
            </p>

            <a href="#"
               class="mb-1 flex items-center rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-gray-100">
                Profile
            </a>

            <a href="#"
               class="mb-1 flex items-center rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-gray-100">
                Addresses
            </a>

            <a href="#"
               class="mb-1 flex items-center rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-gray-100">
                Wishlist
            </a>

            <p class="mb-3 mt-7 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">
                Settings
            </p>

            <a href="#"
               class="mb-1 flex items-center rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-gray-100">
                Settings
            </a>
        </nav>

        <div class="absolute bottom-0 w-64 border-t border-gray-200 p-4">
            <form method="POST" action="#">
                @csrf

                <button type="submit"
                        class="w-full rounded-lg px-3 py-2.5 text-left text-sm font-medium text-red-600 hover:bg-red-50">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Area --}}
    <div class="flex min-w-0 flex-1 flex-col">

        {{-- Topbar --}}
        <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-6">

            <div>
                <h1 class="text-lg font-semibold">
                    {{ $title ?? 'Dashboard' }}
                </h1>
            </div>

            <div class="flex items-center gap-4">

                {{-- Notifications --}}
                <button type="button"
                        class="rounded-lg p-2 hover:bg-gray-100">
                    Notifications
                </button>

                {{-- User --}}
                <div class="flex items-center gap-3">
                    <div class="hidden text-right sm:block">
                        <p class="text-sm font-semibold">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="text-xs text-gray-500">
                            {{ auth()->user()->email }}
                        </p>
                    </div>

                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-200 text-sm font-semibold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>

            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            {{ $slot }}
        </main>

    </div>
</div>

@livewireScripts
</body>
</html>