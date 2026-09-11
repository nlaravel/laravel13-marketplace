<?php

declare(strict_types=1);

namespace App\Services\Customer;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotificationCollection;

class CustomerNotificationService
{
    public function unreadCount(User $user): int
    {
        return $user->unreadNotifications()->count();
    }

    public function latest(User $user, int $limit = 5): DatabaseNotificationCollection
    {
        return $user->notifications()
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function markAsRead(User $user, string $notificationId): void
    {
        $notification = $user->notifications()
            ->where('id', $notificationId)
            ->first();

        if ($notification !== null) {
            $notification->markAsRead();
        }
    }

    public function markAllAsRead(User $user): void
    {
        $user->unreadNotifications->markAsRead();
    }
}
