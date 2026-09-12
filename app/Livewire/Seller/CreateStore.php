<?php

declare(strict_types=1);

namespace App\Livewire\Seller;

use App\Exceptions\SellerException;
use App\Services\Seller\SellerStoreService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.seller-layout')]
class CreateStore extends Component
{
    public string $name = '';

    public string $description = '';

    private SellerStoreService $storeService;

    public function boot(SellerStoreService $storeService): void
    {
        $this->storeService = $storeService;
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

        try {
            $this->storeService->createStore(auth()->id(), $validated);
        } catch (SellerException $exception) {
            $this->dispatch('show-error', message: $exception->getMessage());

            return;
        }

        $this->dispatch('show-success', message: 'Store created successfully and is awaiting approval.');

        $this->redirectRoute('seller.store');
    }

    public function render(): View
    {
        return view('livewire.seller.create-store');
    }
}
