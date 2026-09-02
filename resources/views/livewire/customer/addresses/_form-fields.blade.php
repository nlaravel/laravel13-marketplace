{{-- Address Information --}}
<div class="space-y-5">
    <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 21a2 2 0 01-2.828 0l-4.243-4.343a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>

        <div>
            <h2 class="text-base font-semibold text-slate-900 dark:text-white">
                Address Information
            </h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Enter the main address details.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

        {{-- Label --}}
        <div>
            <label for="label"
                   class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                Address Label
            </label>

            <input
                    id="label"
                    type="text"
                    wire:model="label"
                    placeholder="Home, Work..."
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
            >

            @error('label')
            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Recipient Name --}}
        <div>
            <label for="recipient_name"
                   class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                Recipient Name
            </label>

            <input
                    id="recipient_name"
                    type="text"
                    wire:model="recipient_name"
                    placeholder="Full name"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
            >

            @error('recipient_name')
            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Phone --}}
        <div>
            <label for="phone"
                   class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                Phone
            </label>

            <input
                    id="phone"
                    type="text"
                    wire:model="phone"
                    placeholder="Phone number"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
            >

            @error('phone')
            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Country --}}
        <div>
            <label for="country"
                   class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                Country
            </label>

            <input
                    id="country"
                    type="text"
                    wire:model="country"
                    placeholder="Country"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
            >

            @error('country')
            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- City --}}
        <div>
            <label for="city"
                   class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                City
            </label>

            <input
                    id="city"
                    type="text"
                    wire:model="city"
                    placeholder="City"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
            >

            @error('city')
            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Area --}}
        <div>
            <label for="area"
                   class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                Area
            </label>

            <input
                    id="area"
                    type="text"
                    wire:model="area"
                    placeholder="Area / District"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
            >

            @error('area')
            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Street --}}
        <div class="md:col-span-2">
            <label for="street"
                   class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                Street
            </label>

            <input
                    id="street"
                    type="text"
                    wire:model="street"
                    placeholder="Street name"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
            >

            @error('street')
            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Address Line --}}
        <div class="md:col-span-2">
            <label for="address_line"
                   class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                Address Line
            </label>

            <textarea
                    id="address_line"
                    wire:model="address_line"
                    rows="3"
                    placeholder="Complete address"
                    class="w-full resize-none rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
            ></textarea>

            @error('address_line')
            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>


{{-- Building Details --}}
<div class="border-t border-slate-200 pt-6 dark:border-slate-700">
    <div class="mb-5 flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16M9 7h1m4 0h1M9 11h1m4 0h1M9 15h1m4 0h1"/>
            </svg>
        </div>

        <div>
            <h2 class="text-base font-semibold text-slate-900 dark:text-white">
                Building Details
            </h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Optional building and apartment information.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

        {{-- Building --}}
        <div>
            <label for="building"
                   class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                Building
            </label>

            <input
                    id="building"
                    type="text"
                    wire:model="building"
                    placeholder="Building number or name"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
            >

            @error('building')
            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Apartment --}}
        <div>
            <label for="apartment"
                   class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                Apartment
            </label>

            <input
                    id="apartment"
                    type="text"
                    wire:model="apartment"
                    placeholder="Apartment number"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
            >

            @error('apartment')
            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>


{{-- Map Coordinates --}}
<div class="border-t border-slate-200 pt-6 dark:border-slate-700">
    <div class="mb-5 flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 20l-5-2V6l5 2m0 12l6-2m-6 2V8m6 10l5 2V6l-5-2m0 14V10"/>
            </svg>
        </div>

        <div>
            <h2 class="text-base font-semibold text-slate-900 dark:text-white">
                Map Coordinates
            </h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Optional GPS coordinates for this address.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

        {{-- Latitude --}}
        <div>
            <label for="latitude"
                   class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                Latitude
            </label>

            <input
                    id="latitude"
                    type="text"
                    wire:model="latitude"
                    placeholder="e.g. 31.9539"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
            >

            @error('latitude')
            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Longitude --}}
        <div>
            <label for="longitude"
                   class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                Longitude
            </label>

            <input
                    id="longitude"
                    type="text"
                    wire:model="longitude"
                    placeholder="e.g. 35.9106"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
            >

            @error('longitude')
            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>


{{-- Default Address --}}
<div class="border-t border-slate-200 pt-6 dark:border-slate-700">
    <label class="flex cursor-pointer items-center justify-between rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/50">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.539 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L5.98 8.719c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.95-.69l1.07-3.292z"/>
                </svg>
            </div>

            <div>
                <p class="font-medium text-slate-900 dark:text-white">
                    Set as default address
                </p>

                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Use this address as your primary delivery address.
                </p>
            </div>
        </div>

        <input
                type="checkbox"
                wire:model="is_default"
                class="h-5 w-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-600"
        >
    </label>

    @error('is_default')
    <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>