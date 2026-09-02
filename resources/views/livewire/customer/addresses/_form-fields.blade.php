<div class="space-y-6">

    {{-- Address Label --}}
    <div>
        <label
                for="label"
                class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200"
        >
            Address Label
            <span class="font-normal text-slate-400">
                (Optional)
            </span>
        </label>

        <input
                id="label"
                type="text"
                wire:model="label"
                placeholder="Home, Work, Office..."
                class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-slate-500 dark:focus:ring-slate-800"
        >

        @error('label')
        <p class="mt-2 text-sm text-red-600">
            {{ $message }}
        </p>
        @enderror
    </div>

    {{-- Recipient --}}
    <div>
        <label
                for="recipient_name"
                class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200"
        >
            Recipient Name
        </label>

        <input
                id="recipient_name"
                type="text"
                wire:model="recipient_name"
                placeholder="Full name"
                class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-slate-500 dark:focus:ring-slate-800"
        >

        @error('recipient_name')
        <p class="mt-2 text-sm text-red-600">
            {{ $message }}
        </p>
        @enderror
    </div>

    {{-- Phone --}}
    <div>
        <label
                for="phone"
                class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200"
        >
            Phone
        </label>

        <input
                id="phone"
                type="text"
                wire:model="phone"
                placeholder="+970..."
                class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-slate-500 dark:focus:ring-slate-800"
        >

        @error('phone')
        <p class="mt-2 text-sm text-red-600">
            {{ $message }}
        </p>
        @enderror
    </div>

    {{-- Country / City --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

        <div>
            <label
                    for="country"
                    class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200"
            >
                Country
            </label>

            <input
                    id="country"
                    type="text"
                    wire:model="country"
                    placeholder="Country"
                    class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-slate-500 dark:focus:ring-slate-800"
            >

            @error('country')
            <p class="mt-2 text-sm text-red-600">
                {{ $message }}
            </p>
            @enderror
        </div>

        <div>
            <label
                    for="city"
                    class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200"
            >
                City
            </label>

            <input
                    id="city"
                    type="text"
                    wire:model="city"
                    placeholder="City"
                    class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-slate-500 dark:focus:ring-slate-800"
            >

            @error('city')
            <p class="mt-2 text-sm text-red-600">
                {{ $message }}
            </p>
            @enderror
        </div>

    </div>

    {{-- Area / Street --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

        <div>
            <label
                    for="area"
                    class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200"
            >
                Area
                <span class="font-normal text-slate-400">
                    (Optional)
                </span>
            </label>

            <input
                    id="area"
                    type="text"
                    wire:model="area"
                    placeholder="Area"
                    class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-slate-500 dark:focus:ring-slate-800"
            >

            @error('area')
            <p class="mt-2 text-sm text-red-600">
                {{ $message }}
            </p>
            @enderror
        </div>

        <div>
            <label
                    for="street"
                    class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200"
            >
                Street
            </label>

            <input
                    id="street"
                    type="text"
                    wire:model="street"
                    placeholder="Street name"
                    class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-slate-500 dark:focus:ring-slate-800"
            >

            @error('street')
            <p class="mt-2 text-sm text-red-600">
                {{ $message }}
            </p>
            @enderror
        </div>

    </div>

    {{-- Building / Apartment --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

        <div>
            <label
                    for="building"
                    class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200"
            >
                Building
                <span class="font-normal text-slate-400">
                    (Optional)
                </span>
            </label>

            <input
                    id="building"
                    type="text"
                    wire:model="building"
                    placeholder="Building number/name"
                    class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-slate-500 dark:focus:ring-slate-800"
            >

            @error('building')
            <p class="mt-2 text-sm text-red-600">
                {{ $message }}
            </p>
            @enderror
        </div>

        <div>
            <label
                    for="apartment"
                    class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200"
            >
                Apartment
                <span class="font-normal text-slate-400">
                    (Optional)
                </span>
            </label>

            <input
                    id="apartment"
                    type="text"
                    wire:model="apartment"
                    placeholder="Apartment number"
                    class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-slate-500 dark:focus:ring-slate-800"
            >

            @error('apartment')
            <p class="mt-2 text-sm text-red-600">
                {{ $message }}
            </p>
            @enderror
        </div>

    </div>

    {{-- Address Line --}}
    <div>
        <label
                for="address_line"
                class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200"
        >
            Address Line
        </label>

        <textarea
                id="address_line"
                wire:model="address_line"
                rows="3"
                placeholder="Full address..."
                class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-slate-500 dark:focus:ring-slate-800"
        ></textarea>

        @error('address_line')
        <p class="mt-2 text-sm text-red-600">
            {{ $message }}
        </p>
        @enderror
    </div>

    {{-- Coordinates --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

        <div>
            <label
                    for="latitude"
                    class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200"
            >
                Latitude
                <span class="font-normal text-slate-400">
                    (Optional)
                </span>
            </label>

            <input
                    id="latitude"
                    type="text"
                    wire:model="latitude"
                    placeholder="31.9522"
                    class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-slate-500 dark:text-white dark:focus:ring-slate-800"
            >

            @error('latitude')
            <p class="mt-2 text-sm text-red-600">
                {{ $message }}
            </p>
            @enderror
        </div>

        <div>
            <label
                    for="longitude"
                    class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200"
            >
                Longitude
                <span class="font-normal text-slate-400">
                    (Optional)
                </span>
            </label>

            <input
                    id="longitude"
                    type="text"
                    wire:model="longitude"
                    placeholder="35.2332"
                    class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-slate-500 dark:focus:ring-slate-800"
            >

            @error('longitude')
            <p class="mt-2 text-sm text-red-600">
                {{ $message }}
            </p>
            @enderror
        </div>

    </div>

    {{-- Default --}}
    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900/50">

        <label class="flex cursor-pointer items-start gap-3">

            <input
                    type="checkbox"
                    wire:model="is_default"
                    class="mt-1 h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-slate-600 dark:bg-slate-800"
            >

            <span>
                <span class="block text-sm font-medium text-slate-800 dark:text-slate-100">
                    Set as default address
                </span>

                <span class="mt-1 block text-sm text-slate-500 dark:text-slate-400">
                    This address will be used as your default delivery address.
                </span>
            </span>

        </label>

        @error('is_default')
        <p class="mt-2 text-sm text-red-600">
            {{ $message }}
        </p>
        @enderror

    </div>

</div>