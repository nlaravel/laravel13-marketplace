<?php

declare(strict_types=1);

namespace App\Livewire\Seller\ProductVariants;

use App\Models\Product;
use App\Services\Seller\SellerProductVariantService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.seller-layout')]
class Index extends Component
{
    public Product $product;

    private SellerProductVariantService $variantService;

    public function boot(SellerProductVariantService $variantService): void
    {
        $this->variantService = $variantService;
    }

    public function mount(Product $product): void
    {
        $this->authorize('view', $product);

        $this->product = $product;
    }

    #[Computed]
    public function variants(): Collection
    {
        return $this->variantService->getVariants(auth()->id(), $this->product->id);
    }

    public function render(): View
    {
        return view('livewire.seller.product-variants.index');
    }
}
