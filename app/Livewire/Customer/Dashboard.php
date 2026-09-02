<?php

namespace App\Livewire\Customer;

use App\Services\Customer\CustomerDashboardService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Dashboard extends Component
{
    private function dashboardService(): CustomerDashboardService
    {
        return app(CustomerDashboardService::class);
    }

    #[Computed]
    public function ordersCount(): int
    {
        return $this->dashboardService()
            ->ordersCount(auth()->id());
    }

    #[Computed]
    public function addressesCount(): int
    {
        return $this->dashboardService()
            ->addressesCount(auth()->id());
    }

    #[Computed]
    public function cartItemsCount(): int
    {
        return $this->dashboardService()
            ->cartItemsCount(auth()->id());
    }

    #[Computed]
    public function recentOrders()
    {
        return $this->dashboardService()
            ->recentOrders(auth()->id());
    }

    #[Computed]
    public function defaultAddress()
    {
        return $this->dashboardService()
            ->defaultAddress(auth()->id());
    }

    public function render(): View
    {
        return view('livewire.customer.dashboard');
    }
}