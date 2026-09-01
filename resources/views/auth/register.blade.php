@extends('layouts.auth')

@section('title', 'Create Account | Marketplace')

@section('eyebrow', 'JOIN MARKETPLACE')

@section('hero_title', 'Build your')

@section('hero_highlight', 'marketplace journey.')

@section('hero_description')
    Create your account and get started with a modern marketplace built for customers, sellers, and delivery partners.
@endsection

@section('heading')
    Create your account
@endsection

@section('description')
    Get started with Marketplace today.
@endsection

@section('content')

    <div class="mt-8 bg-white rounded-2xl border border-slate-200 shadow-sm p-8">

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
                action="{{ route('register') }}"
                class="space-y-5"
        >

            @csrf


            {{-- Name --}}
            <div>

                <label
                        for="name"
                        class="block text-sm font-medium text-slate-700 mb-2"
                >
                    Full name
                </label>

                <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Enter your full name"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10"
                >

            </div>


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
                    Password
                </label>

                <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="Create a password"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10"
                >

            </div>


            {{-- Confirm Password --}}
            <div>

                <label
                        for="password_confirmation"
                        class="block text-sm font-medium text-slate-700 mb-2"
                >
                    Confirm password
                </label>

                <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Confirm your password"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10"
                >

            </div>


            {{-- Terms --}}
            <div class="flex items-start gap-3">

                <input
                        id="terms"
                        type="checkbox"
                        required
                        class="mt-1 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                >

                <label
                        for="terms"
                        class="text-sm text-slate-600 leading-relaxed"
                >
                    I agree to the
                    <a
                            href="#"
                            class="font-semibold text-indigo-600 hover:text-indigo-700"
                    >
                        Terms of Service
                    </a>
                    and
                    <a
                            href="#"
                            class="font-semibold text-indigo-600 hover:text-indigo-700"
                    >
                        Privacy Policy
                    </a>.
                </label>

            </div>


            {{-- Submit --}}
            <button
                    type="submit"
                    class="w-full rounded-xl bg-indigo-600 px-4 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20"
            >
                Create account
            </button>

        </form>


        {{-- Login --}}
        <div class="mt-6 text-center">

            <p class="text-sm text-slate-500">

                Already have an account?

                <a
                        href="{{ route('login') }}"
                        class="font-semibold text-indigo-600 hover:text-indigo-700"
                >
                    Sign in
                </a>

            </p>

        </div>

    </div>

@endsection

@section('footer')
    <p class="text-sm text-slate-500">
        By creating an account, you agree to our platform terms.
    </p>
@endsection

