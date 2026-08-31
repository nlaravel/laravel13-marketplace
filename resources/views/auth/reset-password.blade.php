```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reset Password | Marketplace</title>

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
                    ACCOUNT SECURITY
                </p>

                <h1 class="text-5xl font-bold text-white leading-tight">
                    Create a new
                    <span class="text-indigo-400">
                            password.
                        </span>
                </h1>

                <p class="mt-6 text-slate-300 text-lg leading-relaxed">
                    Choose a strong password to keep your Marketplace
                    account secure.
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
                        <span class="text-white font-bold text-lg">
                            M
                        </span>
                </div>

                <span class="text-slate-900 text-xl font-bold">
                        Marketplace
                    </span>

            </div>


            {{-- Header --}}
            <div class="text-center">

                <h2 class="text-3xl font-bold text-slate-900">
                    Reset your password
                </h2>

                <p class="mt-2 text-slate-500">
                    Enter your new password below.
                </p>

            </div>


            {{-- Card --}}
            <div class="mt-8 bg-white rounded-2xl border border-slate-200 shadow-sm p-8">

                @if ($errors->any())

                    <div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-4 py-3">

                        <ul class="text-sm text-red-600 space-y-1">

                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach

                        </ul>

                    </div>

                @endif


                <form
                        method="POST"
                        action="{{ route('password.update') }}"
                        class="space-y-5"
                >

                    @csrf

                    <input
                            type="hidden"
                            name="token"
                            value="{{ $request->route('token') }}"
                    >


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
                                value="{{ old('email', $request->email) }}"
                                required
                                autofocus
                                autocomplete="email"
                                placeholder="you@example.com"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10"
                        >

                    </div>


                    {{-- Password --}}
                    <div>

                        <label
                                for="password"
                                class="block text-sm font-medium text-slate-700 mb-2"
                        >
                            New password
                        </label>

                        <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="Enter your new password"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10"
                        >

                    </div>


                    {{-- Confirm Password --}}
                    <div>

                        <label
                                for="password_confirmation"
                                class="block text-sm font-medium text-slate-700 mb-2"
                        >
                            Confirm new password
                        </label>

                        <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Confirm your new password"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10"
                        >

                    </div>


                    {{-- Submit --}}
                    <button
                            type="submit"
                            class="w-full rounded-xl bg-indigo-600 px-4 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20"
                    >
                        Reset password
                    </button>

                </form>


                {{-- Back to Login --}}
                <div class="mt-6 text-center">

                    <a
                            href="{{ route('login') }}"
                            class="text-sm font-semibold text-indigo-600 hover:text-indigo-700"
                    >
                        ← Back to sign in
                    </a>

                </div>

            </div>


            <p class="mt-6 text-center text-xs text-slate-400">
                Secure authentication powered by Laravel
            </p>

        </div>

    </div>

</div>

</body>
</html>
```
