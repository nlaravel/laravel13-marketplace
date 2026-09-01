@extends('layouts.auth')

@section('title', 'Sign In | Marketplace')

@section('eyebrow', 'WELCOME BACK')

@section('hero_title', 'Your marketplace,')

@section('hero_highlight', 'your way.')

@section('hero_description')
    Sign in to manage your account, orders, products, and marketplace activity.
@endsection

@section('heading')
    Welcome back
@endsection

@section('description')
    Sign in to your Marketplace account.
@endsection

@section('content')

    <div class="mt-8 bg-white rounded-2xl border border-slate-200 shadow-sm p-8">

        {{-- Session Status --}}
        @if (session('status'))
            <div class="mb-6 rounded-xl bg-green-50 border border-green-200 px-4 py-3">
                <p class="text-sm text-green-600">
                    {{ session('status') }}
                </p>
            </div>
        @endif

        {{-- Validation Errors --}}
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
                action="{{ route('login') }}"
                class="space-y-5"
        >

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
                            class="text-sm font-semibold text-indigo-600 hover:text-indigo-700"
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
                        placeholder="Enter your password"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10"
                >

            </div>


            {{-- Remember Me --}}
            <div class="flex items-center">

                <input
                        id="remember"
                        type="checkbox"
                        name="remember"
                        value="1"
                        class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                >

                <label
                        for="remember"
                        class="ml-2 text-sm text-slate-600"
                >
                    Remember me
                </label>

            </div>


            {{-- Submit --}}
            <button
                    type="submit"
                    class="w-full rounded-xl bg-indigo-600 px-4 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20"
            >
                Sign in
            </button>

        </form>


        {{-- Register --}}
        <div class="mt-6 text-center">

            <p class="text-sm text-slate-500">

                Don't have an account?

                <a
                        href="{{ route('register') }}"
                        class="font-semibold text-indigo-600 hover:text-indigo-700"
                >
                    Create an account
                </a>

            </p>

        </div>

    </div>

@endsection

@section('footer')
    <p class="text-sm text-slate-500">
        New to Marketplace?
        <a
                href="{{ route('register') }}"
                class="font-semibold text-indigo-600 hover:text-indigo-700"
        >
            Create your account
        </a>
    </p>
@endsection
```
