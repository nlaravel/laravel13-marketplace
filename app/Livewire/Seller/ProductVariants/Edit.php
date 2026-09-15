<?php

declare(strict_types=1);

namespace App\Livewire\Seller\ProductVariants;

use App\Exceptions\SellerException;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\Seller\SellerProductVariantService;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.seller-layout')]
class Edit extends Component
{
    public Product $product;

    public ProductVariant $variant;

    public string $sku = '';

    public string $price = '';

    public string $compareAtPrice = '';

    public bool $isActive = true;

    private SellerProductVariantService $variantService;

    public function boot(SellerProductVariantService $variantService): void
    {
        $this->variantService = $variantService;
    }

    public function mount(Product $product, ProductVariant $variant): void
    {
        $this->authorize('update', $product);

        $this->product = $product;

        if ($variant->product_id !== $product->id) {
            abort(404);
        }

        $this->variant = $variant;

        $this->sku = $variant->sku;
        $this->price = (string) $variant->price;
        $this->compareAtPrice = $variant->compare_at_price !== null
            ? (string) $variant->compare_at_price
            : '';
        $this->isActive = $variant->is_active;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('product_variants', 'sku')
                    ->ignore($this->variant->id),
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'compareAtPrice' => [
                'nullable',
                'numeric',
                'min:0',
                'gte:price',
            ],
            'isActive' => [
                'boolean',
            ],
        ]);

        $this->authorize('update', $this->product);

        try {
            $this->variantService->updateVariant(auth()->id(), $this->variant->id, [
                'sku' => $validated['sku'],
                'price' => $validated['price'],
                'compare_at_price' => $validated['compareAtPrice'] !== ''
                    ? $validated['compareAtPrice']
                    : null,
                'is_active' => $validated['isActive'],
            ]);
        } catch (SellerException $exception) {
            $this->addError('sku', $exception->getMessage());

            return;
        }

        $this->dispatch('show-success', message: 'Product variant updated successfully.');

        $this->redirectRoute('seller.product.variants.index', $this->product);
    }

    public function render(): View
    {
        return view('livewire.seller.product-variants.edit');
    }
}
