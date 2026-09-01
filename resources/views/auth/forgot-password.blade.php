@extends('layouts.auth')

@section('title', 'Forgot Password | Marketplace')

@section('eyebrow', 'ACCOUNT RECOVERY')

@section('hero_title', 'Recover your')

@section('hero_highlight', 'account.')

@section('hero_description')
    Reset your password securely and get back to your Marketplace account.
@endsection

@section('heading')
    Forgot your password?
@endsection

@section('description')
    Enter your email and we'll send you a password reset link.
@endsection

@section('content')

    <div class="mt-8 bg-white rounded-2xl border border-slate-200 shadow-sm p-8">

        {{-- Status --}}
        @if (session('status'))
            <div class="mb-6 rounded-xl bg-green-50 border border-green-200 px-4 py-3">
                <p class="text-sm text-green-700">
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
                action="{{ route('password.email') }}"
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

            {{-- Submit --}}
            <button
                    type="submit"
                    class="w-full rounded-xl bg-indigo-600 px-4 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20"
            >
                Send reset link
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

@section('footer')
    <p class="text-sm text-slate-500">
        We'll send a secure password reset link to your email.
    </p>
@endsection