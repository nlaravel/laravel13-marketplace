```blade
@extends('layouts.auth')

@section('title', 'Reset Password | Marketplace')

@section('eyebrow', 'ACCOUNT SECURITY')

@section('hero_title', 'Create a new')

@section('hero_highlight', 'password.')

@section('hero_description')
    Choose a strong password to keep your Marketplace account secure.
@endsection

@section('heading')
    Reset your password
@endsection

@section('description')
    Enter your new password below.
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
                action="{{ route('password.update') }}"
                class="space-y-5"
        >

            @csrf

            {{-- Reset Token --}}
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


            {{-- New Password --}}
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

@endsection

