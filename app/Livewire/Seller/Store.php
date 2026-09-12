<?php

declare(strict_types=1);

namespace App\Livewire\Seller;

use App\Services\Seller\SellerStoreService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.seller-layout')]
class Store extends Component
{
    private SellerStoreService $storeService;

    public function boot(SellerStoreService $storeService): void
    {
        $this->storeService = $storeService;
    }

    #[Computed]
    public function stores(): Collection
    {
        return $this->storeService->getStores(auth()->id());
    }

    public function render(): View
    {
        return view('livewire.seller.store');
    }
}
