<?php

declare(strict_types=1);

namespace App\Livewire\Customer\Notifications;

use App\Services\Customer\CustomerNotificationService;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.customer-layout')]
class Index extends Component
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

        $this->notifications = $this->notificationService->latest($user, 50);
    }

    public function render()
    {
        return view('livewire.customer.notifications.index');
    }
}
