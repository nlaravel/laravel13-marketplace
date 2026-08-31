<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | Marketplace</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50">

<div class="min-h-screen flex items-center justify-center px-6">

    <div class="text-center">

        <div class="mx-auto w-14 h-14 rounded-2xl bg-indigo-600 flex items-center justify-center">
                <span class="text-white text-xl font-bold">
                    M
                </span>
        </div>

        <h1 class="mt-6 text-3xl font-bold text-slate-900">
            Welcome, {{ auth()->user()->name }}
        </h1>

        <p class="mt-2 text-slate-500">
            Welcome to your Marketplace dashboard.
        </p>

        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf

            <button
                    type="submit"
                    class="rounded-xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white hover:bg-slate-800 transition"
            >
                Logout
            </button>
        </form>

    </div>

</div>

</body>
</html>