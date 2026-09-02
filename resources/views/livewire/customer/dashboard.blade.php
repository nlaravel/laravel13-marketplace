<div class="space-y-6">

    {{-- Welcome --}}
    <div
            class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200
               dark:bg-gray-900 dark:ring-gray-800"
    >
        <div class="flex items-center justify-between gap-4">

            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Welcome back
                </p>

                <h2 class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                    {{ auth()->user()->name }}
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Here's what's happening with your account.
                </p>
            </div>

            <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center
                       rounded-full bg-gray-900 text-lg font-bold text-white
                       dark:bg-white dark:text-gray-900"
            >
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

        </div>
    </div>


    {{-- Statistics --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

        {{-- Orders --}}
        <div
                class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200
                   dark:bg-gray-900 dark:ring-gray-800"
        >
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                My Orders
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                {{ $ordersCount }}
            </p>

            <a
                    href="#"
                    class="mt-4 inline-block text-sm font-medium text-gray-700
                       hover:text-gray-900 dark:text-gray-300 dark:hover:text-white"
            >
                View orders →
            </a>
        </div>


        {{-- Addresses --}}
        <div
                class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200
                   dark:bg-gray-900 dark:ring-gray-800"
        >
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Addresses
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                {{ $addressesCount }}
            </p>

            <a
                    href="#"
                    class="mt-4 inline-block text-sm font-medium text-gray-700
                       hover:text-gray-900 dark:text-gray-300 dark:hover:text-white"
            >
                Manage addresses →
            </a>
        </div>


        {{-- Cart --}}
        <div
                class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200
                   dark:bg-gray-900 dark:ring-gray-800"
        >
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Cart Items
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                {{ $cartItemsCount }}
            </p>

            <a
                    href="#"
                    class="mt-4 inline-block text-sm font-medium text-gray-700
                       hover:text-gray-900 dark:text-gray-300 dark:hover:text-white"
            >
                View cart →
            </a>
        </div>

    </div>


    {{-- Content --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Recent Orders --}}
        <div
                class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1
                   ring-gray-200 lg:col-span-2
                   dark:bg-gray-900 dark:ring-gray-800"
        >

            <div
                    class="border-b border-gray-200 px-6 py-4
                       dark:border-gray-800"
            >
                <h3 class="font-semibold text-gray-900 dark:text-white">
                    Recent Orders
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Your latest orders
                </p>
            </div>


            <div class="divide-y divide-gray-100 dark:divide-gray-800">

                @forelse ($recentOrders as $order)

                    <div
                            class="flex items-center justify-between gap-4
                               px-6 py-4"
                    >

                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">
                                Order #{{ $order->id }}
                            </p>

                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ $order->created_at?->format('M d, Y') }}
                            </p>
                        </div>


                        <div class="text-right">

                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ number_format((float) $order->total, 2) }}
                                {{ $order->currency ?? 'USD' }}
                            </p>

                            <span
                                    class="mt-1 inline-flex rounded-full
                                       bg-gray-100 px-2.5 py-1 text-xs
                                       font-medium text-gray-700
                                       dark:bg-gray-800 dark:text-gray-300"
                            >
                                {{ $order->status?->value ?? $order->status }}
                            </span>

                        </div>

                    </div>

                @empty

                    <div class="px-6 py-12 text-center">

                        <p class="font-medium text-gray-900 dark:text-white">
                            No orders yet
                        </p>

                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Your recent orders will appear here.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>


        {{-- Default Address --}}
        <div
                class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200
                   dark:bg-gray-900 dark:ring-gray-800"
        >

            <div
                    class="border-b border-gray-200 px-6 py-4
                       dark:border-gray-800"
            >
                <h3 class="font-semibold text-gray-900 dark:text-white">
                    Default Address
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Your primary delivery address
                </p>
            </div>


            <div class="p-6">

                @if ($defaultAddress)

                    <div class="space-y-3">

                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $defaultAddress->label ?? 'Address' }}
                            </p>
                        </div>


                        <div
                                class="text-sm leading-6 text-gray-600
                                   dark:text-gray-400"
                        >

                            @if ($defaultAddress->address_line_1)
                                <p>
                                    {{ $defaultAddress->address_line_1 }}
                                </p>
                            @endif

                            @if ($defaultAddress->address_line_2)
                                <p>
                                    {{ $defaultAddress->address_line_2 }}
                                </p>
                            @endif

                            @if ($defaultAddress->city)
                                <p>
                                    {{ $defaultAddress->city }}
                                </p>
                            @endif

                            @if ($defaultAddress->state)
                                <p>
                                    {{ $defaultAddress->state }}
                                </p>
                            @endif

                            @if ($defaultAddress->postal_code)
                                <p>
                                    {{ $defaultAddress->postal_code }}
                                </p>
                            @endif

                        </div>


                        @if ($defaultAddress->phone)

                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $defaultAddress->phone }}
                            </p>

                        @endif

                    </div>

                @else

                    <div class="py-6 text-center">

                        <p class="font-medium text-gray-900 dark:text-white">
                            No default address
                        </p>

                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Add an address to make checkout faster.
                        </p>

                        <a
                                href="#"
                                class="mt-4 inline-flex rounded-lg bg-gray-900
                                   px-4 py-2 text-sm font-medium text-white
                                   hover:bg-gray-800
                                   dark:bg-white dark:text-gray-900
                                   dark:hover:bg-gray-200"
                        >
                            Add Address
                        </a>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- Quick Actions --}}
    <div
            class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200
               dark:bg-gray-900 dark:ring-gray-800"
    >

        <h3 class="font-semibold text-gray-900 dark:text-white">
            Quick Actions
        </h3>


        <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">

            {{-- Products --}}
            <a
                    href="#"
                    class="rounded-xl border border-gray-200 p-4
                       transition hover:bg-gray-50
                       dark:border-gray-800 dark:hover:bg-gray-800"
            >
                <p class="font-medium text-gray-900 dark:text-white">
                    Browse Products
                </p>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Find products in the marketplace
                </p>
            </a>


            {{-- Orders --}}
            <a
                    href="#"
                    class="rounded-xl border border-gray-200 p-4
                       transition hover:bg-gray-50
                       dark:border-gray-800 dark:hover:bg-gray-800"
            >
                <p class="font-medium text-gray-900 dark:text-white">
                    My Orders
                </p>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Track your orders
                </p>
            </a>


            {{-- Cart --}}
            <a
                    href="#"
                    class="rounded-xl border border-gray-200 p-4
                       transition hover:bg-gray-50
                       dark:border-gray-800 dark:hover:bg-gray-800"
            >
                <p class="font-medium text-gray-900 dark:text-white">
                    My Cart
                </p>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Review your shopping cart
                </p>
            </a>


            {{-- Profile --}}
            <a
                    href="#"
                    class="rounded-xl border border-gray-200 p-4
                       transition hover:bg-gray-50
                       dark:border-gray-800 dark:hover:bg-gray-800"
            >
                <p class="font-medium text-gray-900 dark:text-white">
                    Profile
                </p>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Manage your account
                </p>
            </a>

        </div>

    </div>

</div>