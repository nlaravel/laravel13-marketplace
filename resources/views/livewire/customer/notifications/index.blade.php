<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                Notifications
            </h1>

            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Stay up to date with your marketplace activity.
            </p>
        </div>

        @if ($unreadCount > 0)
            <button
                    type="button"
                    wire:click="markAllAsRead"
                    class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100"
            >
                Mark all as read
            </button>
        @endif
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
        @if ($notifications->isEmpty())
            <div class="px-6 py-16 text-center">
                <div
                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400"
                >
                    ✓
                </div>

                <h2 class="mt-4 text-sm font-semibold text-slate-800 dark:text-slate-100">
                    No notifications
                </h2>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    You're all caught up.
                </p>
            </div>
        @else
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
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
                            class="flex gap-4 px-6 py-5 transition hover:bg-slate-50 dark:hover:bg-slate-800/50 {{ $notification->read_at === null ? 'bg-blue-50/40 dark:bg-blue-950/10' : '' }}"
                    >
                        <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400"
                        >
                            ✓
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                                    {{ $title }}
                                </p>

                                @if ($notification->read_at === null)
                                    <span class="w-fit rounded-full bg-blue-50 px-2 py-1 text-[10px] font-bold text-blue-600 dark:bg-blue-950/40 dark:text-blue-400">
                                        New
                                    </span>
                                @endif
                            </div>

                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                {{ $message }}
                            </p>

                            <div class="mt-2 flex items-center gap-3">
                                <span class="text-xs text-slate-400">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>

                                @if ($notification->read_at === null)
                                    <button
                                            type="button"
                                            wire:click="markAsRead('{{ $notification->id }}')"
                                            class="text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400"
                                    >
                                        Mark as read
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>