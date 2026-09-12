<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use App\Services\Customer\CustomerNotificationService;
use Illuminate\Notifications\DatabaseNotificationCollection;

trait ManagesCustomerNotifications
{
    public DatabaseNotificationCollection $notifications;

    public int $unreadCount = 0;

    private CustomerNotificationService $notificationService;

    public function bootManagesCustomerNotifications(CustomerNotificationService $notificationService): void
    {
        $this->notificationService = $notificationService;
    }

    public function markAsRead(string $notificationId): void
    {
        $this->notificationService->markAsRead(auth()->user(), $notificationId);

        $this->loadNotifications();
    }

    public function markAllAsRead(): void
    {
        $this->notificationService->markAllAsRead(auth()->user());

        $this->loadNotifications();
    }

    protected function loadNotifications(): void
    {
        $user = auth()->user();

        $this->unreadCount = $this->notificationService->unreadCount($user);

        $this->notifications = $this->notificationService->latest($user, $this->notificationsLimit());
    }

    protected function notificationsLimit(): int
    {
        return 5;
    }
}
