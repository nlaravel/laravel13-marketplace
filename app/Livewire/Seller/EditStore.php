<?php

declare(strict_types=1);

namespace App\Livewire\Seller;

use App\Models\Store;
use App\Services\Seller\SellerStoreService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.seller-layout')]
class EditStore extends Component
{
    public Store $store;

    public string $name = '';

    public string $description = '';

    private SellerStoreService $storeService;

    public function boot(SellerStoreService $storeService): void
    {
        $this->storeService = $storeService;
    }

    public function mount(Store $store): void
    {
        $this->store = $this->storeService->getStore(auth()->id(), $store->id);

        $this->name = $this->store->name;
        $this->description = $this->store->description ?? '';
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $this->storeService->updateStore(auth()->id(), $this->store->id, $validated);

        $this->dispatch('show-success', message: 'Store updated successfully.');

        $this->redirectRoute('seller.store');
    }

    public function render(): View
    {
        return view('livewire.seller.edit-store');
    }
}
