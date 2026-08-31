<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Marketplace</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50">

<div class="min-h-screen flex">

    {{-- Left Side --}}
    <div class="hidden lg:flex lg:w-1/2 bg-slate-900 relative overflow-hidden">

        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900"></div>

        <div class="relative z-10 flex flex-col justify-between w-full p-12">

            <div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center">
                        <span class="text-white font-bold text-lg">M</span>
                    </div>

                    <span class="text-white text-xl font-bold">
                            Marketplace
                        </span>
                </div>
            </div>

            <div class="max-w-lg">

                <p class="text-indigo-400 font-semibold mb-4">
                    YOUR MARKETPLACE
                </p>

                <h1 class="text-5xl font-bold text-white leading-tight">
                    Discover products.
                    <span class="text-indigo-400">
                            Shop smarter.
                        </span>
                </h1>

                <p class="mt-6 text-slate-300 text-lg leading-relaxed">
                    Find the products you need from trusted sellers
                    and enjoy a simple, secure shopping experience.
                </p>

            </div>

            <div class="text-slate-500 text-sm">
                © {{ date('Y') }} Marketplace. All rights reserved.
            </div>

        </div>

    </div>


    {{-- Right Side --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12">

        <div class="w-full max-w-md">

            {{-- Mobile Logo --}}
            <div class="lg:hidden flex items-center justify-center gap-3 mb-10">

                <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center">
                    <span class="text-white font-bold text-lg">M</span>
                </div>

                <span class="text-slate-900 text-xl font-bold">
                        Marketplace
                    </span>

            </div>


            {{-- Header --}}
            <div class="text-center">

                <h2 class="text-3xl font-bold text-slate-900">
                    Welcome back
                </h2>

                <p class="mt-2 text-slate-500">
                    Sign in to continue to your account
                </p>

            </div>


            {{-- Login Card --}}
            <div class="mt-8 bg-white rounded-2xl border border-slate-200 shadow-sm p-8">

                @if (session('status'))
                    <div class="mb-5 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                        {{ session('status') }}
                    </div>
                @endif


                @if ($errors->any())
                    <div class="mb-5 rounded-lg bg-red-50 border border-red-200 px-4 py-3">

                        <ul class="text-sm text-red-600 space-y-1">

                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach

                        </ul>

                    </div>
                @endif


                <form method="POST" action="{{ route('login') }}" class="space-y-5">

                    @csrf


                    {{-- Email --}}
                    <div>

                        <label
                                for="email"
                                class="block text-sm font-medium text-slate-700 mb-2"
                        >
                            Email address
                        </label>

                        <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="email"
                                placeholder="you@example.com"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10"
                        >

                    </div>


                    {{-- Password --}}
                    <div>

                        <div class="flex items-center justify-between mb-2">

                            <label
                                    for="password"
                                    class="block text-sm font-medium text-slate-700"
                            >
                                Password
                            </label>

                            <a
                                    href="{{ route('password.request') }}"
                                    class="text-sm font-medium text-indigo-600 hover:text-indigo-700"
                            >
                                Forgot password?
                            </a>

                        </div>

                        <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10"
                        >

                    </div>


                    {{-- Remember --}}
                    <div class="flex items-center">

                        <input
                                id="remember"
                                type="checkbox"
                                name="remember"
                                class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                        >

                        <label
                                for="remember"
                                class="ml-2 text-sm text-slate-600"
                        >
                            Remember me
                        </label>

                    </div>


                    {{-- Button --}}
                    <button
                            type="submit"
                            class="w-full rounded-xl bg-indigo-600 px-4 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20"
                    >
                        Sign in
                    </button>

                </form>


                {{-- Register --}}
                <div class="mt-6 text-center text-sm text-slate-500">

                    Don't have an account?

                    <a
                            href="{{ route('register') }}"
                            class="font-semibold text-indigo-600 hover:text-indigo-700"
                    >
                        Create an account
                    </a>

                </div>

            </div>


            {{-- Footer --}}
            <p class="mt-6 text-center text-xs text-slate-400">
                Secure authentication powered by Laravel
            </p>

        </div>

    </div>

</div>

</body>
</html>