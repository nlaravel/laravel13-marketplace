<?php

declare(strict_types=1);

namespace App\Livewire\Customer\Notifications;

use App\Livewire\Concerns\ManagesCustomerNotifications;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.customer-layout')]
class Index extends Component
{
    use ManagesCustomerNotifications;

    public function mount(): void
    {
        $this->loadNotifications();
    }

    protected function notificationsLimit(): int
    {
        return 50;
    }

    public function render(): View
    {
        return view('livewire.customer.notifications.index');
    }
}
