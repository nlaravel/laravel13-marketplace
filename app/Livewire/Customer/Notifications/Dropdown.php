<?php

declare(strict_types=1);

namespace App\Livewire\Customer\Notifications;

use App\Services\Customer\CustomerNotificationService;
use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Livewire\Component;

class Dropdown extends Component
{
    public DatabaseNotificationCollection $notifications;

    public int $unreadCount = 0;

    private CustomerNotificationService $notificationService;

    public function boot(CustomerNotificationService $notificationService): void
    {
        $this->notificationService = $notificationService;
    }

    public function mount(): void
    {
        $this->loadNotifications();
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

    private function loadNotifications(): void
    {
        $user = auth()->user();

        $this->unreadCount = $this->notificationService->unreadCount($user);

        $this->notifications = $this->notificationService->latest($user);
    }

    public function render(): View
    {
        return view('livewire.customer.notifications.dropdown');
    }
}
