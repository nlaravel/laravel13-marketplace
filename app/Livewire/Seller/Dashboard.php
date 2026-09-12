<?php

declare(strict_types=1);

namespace App\Livewire\Seller;

use App\Services\Seller\SellerDashboardService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.seller-layout')]
class Dashboard extends Component
{
    private SellerDashboardService $dashboardService;

    public function boot(SellerDashboardService $dashboardService): void
    {
        $this->dashboardService = $dashboardService;
    }

    #[Computed]
    public function storesCount(): int
    {
        return $this->dashboardService->storesCount(auth()->id());
    }

    #[Computed]
    public function ordersCount(): int
    {
        return $this->dashboardService->ordersCount(auth()->id());
    }

    #[Computed]
    public function recentOrders(): Collection
    {
        return $this->dashboardService->recentOrders(auth()->id());
    }

    #[Computed]
    public function ordersByStatus(): array
    {
        return $this->dashboardService->ordersByStatus(auth()->id());
    }

    public function render(): View
    {
        return view('livewire.seller.dashboard');
    }
}
