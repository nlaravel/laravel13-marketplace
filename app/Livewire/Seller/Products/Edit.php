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
class Edit extends Component
{
    public Product $product;

    public int $categoryId = 0;

    public string $name = '';

    public string $description = '';

    private SellerProductService $productService;

    public function boot(SellerProductService $productService): void
    {
        $this->productService = $productService;
    }

    public function mount(Product $product): void
    {
        $this->product = $this->productService->getProduct(auth()->id(), $product->id);

        $this->authorize('update', $this->product);

        $this->categoryId = $this->product->category_id;
        $this->name = $this->product->name;
        $this->description = $this->product->description ?? '';
    }

    #[Computed]
    public function categories(): Collection
    {
        return $this->productService->getActiveCategories();
    }

    public function save(): void
    {
        $validated = $this->validate([
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

        $this->authorize('update', $this->product);

        $this->product = $this->productService->updateProduct(auth()->id(), $this->product->id, [
            'category_id' => $validated['categoryId'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        $this->dispatch('show-success', message: 'Product updated successfully.');

        $this->redirectRoute('seller.product.index');
    }

    public function render(): View
    {
        return view('livewire.seller.products.edit');
    }
}
