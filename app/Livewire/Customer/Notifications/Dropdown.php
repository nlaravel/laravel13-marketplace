<?php

declare(strict_types=1);

namespace App\Livewire\Customer\Notifications;

use App\Livewire\Concerns\ManagesCustomerNotifications;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Dropdown extends Component
{
    use ManagesCustomerNotifications;

    public int $notificationsLimit = 5;

    public function mount(): void
    {
        $this->loadNotifications();
    }

    public function render(): View
    {
        return view('livewire.customer.notifications.dropdown');
    }
}
