<div class="min-h-full">

    {{-- Page Header --}}
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-center gap-4">

            {{-- Icon --}}
            <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400"
            >
                <svg
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                >
                    <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"
                    />

                    <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4.5 20.25a8.25 8.25 0 0115 0"
                    />
                </svg>
            </div>

            {{-- Title --}}
            <div>
                <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">
                    Profile
                </h1>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Manage your personal information.
                </p>
            </div>

        </div>
    </div>


    {{-- Profile Card --}}
    <div
            class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800"
    >

        {{-- Card Header --}}
        <div class="border-b border-slate-200 px-6 py-5 dark:border-slate-700">

            <h2 class="text-base font-semibold text-slate-900 dark:text-white">
                Personal Information
            </h2>

            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Update your name and phone number.
            </p>

        </div>


        {{-- Form --}}
        <form
                wire:submit="updateProfile"
                class="space-y-6 p-6"
        >

            {{-- Full Name --}}
            <div>

                <label
                        for="name"
                        class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300"
                >
                    Full Name
                </label>

                <input
                        id="name"
                        type="text"
                        wire:model="name"
                        autocomplete="name"
                        placeholder="Enter your full name"
                        class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500"
                >

                @error('name')
                <p class="mt-2 text-sm text-rose-600 dark:text-rose-400">
                    {{ $message }}
                </p>
                @enderror

            </div>


            {{-- Phone --}}
            <div>

                <label
                        for="phone"
                        class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300"
                >
                    Phone Number
                </label>

                <input
                        id="phone"
                        type="text"
                        wire:model="phone"
                        autocomplete="tel"
                        placeholder="Enter your phone number"
                        class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500"
                >

                @error('phone')
                <p class="mt-2 text-sm text-rose-600 dark:text-rose-400">
                    {{ $message }}
                </p>
                @enderror

            </div>


            {{-- Email --}}
            <div>

                <label
                        for="email"
                        class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300"
                >
                    Email Address
                </label>

                <input
                        id="email"
                        type="email"
                        value="{{ auth()->user()->email }}"
                        disabled
                        class="block w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-500 outline-none dark:border-slate-700 dark:bg-slate-900/50 dark:text-slate-400"
                >

                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                    Email address cannot be changed from this page.
                </p>

            </div>


            {{-- Divider --}}
            <div class="border-t border-slate-200 dark:border-slate-700"></div>


            {{-- Actions --}}
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">

                {{-- Cancel --}}
                <a
                        href="{{ route('customer.dashboard') }}"
                        wire:navigate
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700"
                >
                    Cancel
                </a>


                {{-- Save --}}
                <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="updateProfile"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60"
                >

                    {{-- Loading Spinner --}}
                    <svg
                            wire:loading
                            wire:target="updateProfile"
                            class="h-4 w-4 animate-spin"
                            viewBox="0 0 24 24"
                            fill="none"
                    >
                        <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                        />

                        <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                        />
                    </svg>


                    {{-- Normal --}}
                    <span
                            wire:loading.remove
                            wire:target="updateProfile"
                    >
                        Save Changes
                    </span>


                    {{-- Loading --}}
                    <span
                            wire:loading
                            wire:target="updateProfile"
                    >
                        Saving...
                    </span>

                </button>

            </div>

        </form>

    </div>

</div>

