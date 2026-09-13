<?php

declare(strict_types=1);

namespace App\Livewire\Seller\Products;

use App\Services\Seller\SellerProductService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.seller-layout')]
class Index extends Component
{
    private SellerProductService $productService;

    public function boot(SellerProductService $productService): void
    {
        $this->productService = $productService;
    }

    #[Computed]
    public function products(): Collection
    {
        return $this->productService->getProducts(auth()->id());
    }

    public function render(): View
    {
        return view('livewire.seller.products.index');
    }
}
