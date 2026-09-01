<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Authentication | Marketplace')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50">

<div class="min-h-screen flex">

    {{-- Left Side --}}
    <div class="hidden lg:flex lg:w-1/2 bg-slate-900 relative overflow-hidden">

        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900"></div>

        <div class="relative z-10 flex flex-col justify-between w-full p-12">

            {{-- Brand --}}
            <div>
                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center">
                        <span class="text-white font-bold text-lg">
                            M
                        </span>
                    </div>

                    <span class="text-white text-xl font-bold">
                        Marketplace
                    </span>

                </div>
            </div>

            {{-- Hero Content --}}
            <div class="max-w-lg">

                <p class="text-indigo-400 font-semibold mb-4">
                    @yield('eyebrow', 'MARKETPLACE PLATFORM')
                </p>

                <h1 class="text-5xl font-bold text-white leading-tight">
                    @yield('hero_title', 'Everything you need,')
                    <span class="text-indigo-400">
                        @yield('hero_highlight', 'in one place.')
                    </span>
                </h1>

                <p class="mt-6 text-slate-300 text-lg leading-relaxed">
                    @yield(
                        'hero_description',
                        'A modern marketplace platform built for customers, sellers, and delivery partners.'
                    )
                </p>

            </div>

            {{-- Copyright --}}
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

                @hasSection('heading')
                    <h2 class="text-3xl font-bold text-slate-900">
                        @yield('heading')
                    </h2>
                @endif

                @hasSection('description')
                    <p class="mt-2 text-slate-500">
                        @yield('description')
                    </p>
                @endif

            </div>


            {{-- Main Content --}}
            @yield('content')


            {{-- Footer --}}
            @hasSection('footer')
                <div class="mt-6 text-center">
                    @yield('footer')
                </div>
            @endif

            <p class="mt-6 text-center text-xs text-slate-400">
                Secure authentication powered by Laravel
            </p>

        </div>

    </div>

</div>

</body>
</html>

