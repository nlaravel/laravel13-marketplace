<div>
    <details class="relative">
        <summary
                class="relative flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white">

            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 01-6 0"
                />
            </svg>

            @if ($unreadCount > 0)
                <span
                        class="absolute right-2 top-2 h-2 w-2 rounded-full bg-red-500 ring-2 ring-white dark:ring-slate-900">
                </span>
            @endif
        </summary>

        <div
                class="absolute right-0 top-14 z-50 w-80 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-900">

            <div
                    class="flex items-center justify-between border-b border-slate-100 px-4 py-4 dark:border-slate-800">

                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">
                        Notifications
                    </h3>

                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Stay up to date
                    </p>
                </div>

                @if ($unreadCount > 0)
                    <button
                            type="button"
                            wire:click="markAllAsRead"
                            class="text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                        Mark all as read
                    </button>
                @endif
            </div>

            @if ($notifications->isEmpty())
                <div class="px-4 py-8 text-center">
                    <div
                            class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                        ✓
                    </div>

                    <p class="mt-3 text-sm font-semibold text-slate-700 dark:text-slate-200">
                        No notifications
                    </p>

                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        You're all caught up.
                    </p>
                </div>
            @else
                <div class="max-h-96 divide-y divide-slate-100 overflow-y-auto dark:divide-slate-800">
                    @foreach ($notifications as $notification)
                        @php
                            $type = $notification->data['type'] ?? 'general';

                            $title = match ($type) {
                                'payment_succeeded' => 'Payment successful',
                                default => 'Notification',
                            };

                            $message = match ($type) {
                                'payment_succeeded' => 'Your payment was completed successfully.',
                                default => 'You have a new notification.',
                            };
                        @endphp

                        <div
                                class="flex gap-3 px-4 py-4 transition hover:bg-slate-50 dark:hover:bg-slate-800 {{ $notification->read_at === null ? 'bg-blue-50/40 dark:bg-blue-950/10' : '' }}">

                            <div
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400">
                                ✓
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                                    {{ $title }}
                                </p>

                                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                                    {{ $message }}
                                </p>

                                <div class="mt-1 flex items-center justify-between gap-2">
                                    <p class="text-[10px] text-slate-400">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>

                                    @if ($notification->read_at === null)
                                        <button
                                                type="button"
                                                wire:click="markAsRead('{{ $notification->id }}')"
                                                class="text-[10px] font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                            Mark as read
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="border-t border-slate-100 p-3 dark:border-slate-800">
                <a
                        href="{{ route('customer.notifications.index') }}"
                        class="block rounded-xl bg-slate-50 px-3 py-2.5 text-center text-xs font-semibold text-slate-700 transition hover:bg-slate-100 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                    View all notifications
                </a>
            </div>
        </div>
    </details>
</div>