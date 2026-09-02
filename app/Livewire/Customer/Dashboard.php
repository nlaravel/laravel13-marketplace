<?php

namespace App\Livewire\Customer;

use App\Models\Address;
use App\Services\Customer\CustomerDashboardService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Dashboard extends Component
{
    private CustomerDashboardService $dashboardService;

    public function boot(CustomerDashboardService $dashboardService): void
    {
        $this->dashboardService = $dashboardService;
    }

    #[Computed]
    public function ordersCount(): int
    {
        return $this->dashboardService->ordersCount(
            auth()->id()
        );
    }

    #[Computed]
    public function addressesCount(): int
    {
        return $this->dashboardService->addressesCount(
            auth()->id()
        );
    }

    #[Computed]
    public function cartItemsCount(): int
    {
        return $this->dashboardService->cartItemsCount(
            auth()->id()
        );
    }

    #[Computed]
    public function recentOrders(): Collection
    {
        return $this->dashboardService->recentOrders(
            auth()->id()
        );
    }

    #[Computed]
    public function defaultAddress(): ?Address
    {
        return $this->dashboardService->defaultAddress(
            auth()->id()
        );
    }

    public function render(): View
    {
        return view('livewire.customer.dashboard');
    }
}
