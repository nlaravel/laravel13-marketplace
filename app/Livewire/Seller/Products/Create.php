<?php

declare(strict_types=1);

namespace App\Livewire\Seller\Products;

use App\Models\Product;
use App\Services\Seller\SellerProductService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.seller-layout')]
class Create extends Component
{
    public int $storeId = 0;

    public int $categoryId = 0;

    public string $name = '';

    public string $description = '';

    private SellerProductService $productService;

    public function boot(SellerProductService $productService): void
    {
        $this->productService = $productService;
    }

    public function mount(): void
    {
        $this->authorize('create', Product::class);
    }

    #[Computed]
    public function stores(): Collection
    {
        return $this->productService->getApprovedStores(auth()->id());
    }

    #[Computed]
    public function categories(): Collection
    {
        return $this->productService->getActiveCategories();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'storeId' => [
                'required',
                'integer',
            ],
            'categoryId' => [
                'required',
                'integer',
            ],
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

        $this->authorize('create', Product::class);

        $this->productService->createProduct(auth()->id(), $validated['storeId'], [
            'category_id' => $validated['categoryId'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        $this->dispatch('show-success', message: 'Product created successfully.');

        $this->redirectRoute('seller.product.index');
    }

    public function render(): View
    {
        return view('livewire.seller.products.create');
    }
}
