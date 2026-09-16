<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Seller\ProductVariantRequest;
use App\Http\Resources\Api\Seller\ProductVariantResource;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\Seller\SellerProductVariantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class SellerProductVariantController extends Controller
{
    public function __construct(private readonly SellerProductVariantService $variantService) {}

    public function index(Product $product): AnonymousResourceCollection
    {
        Gate::authorize('view', $product);

        $variants = $this->variantService->getVariants(auth()->id(), $product->id);

        return ProductVariantResource::collection($variants);
    }

    public function store(ProductVariantRequest $request, Product $product): ProductVariantResource
    {
        Gate::authorize('update', $product);

        $variant = $this->variantService->createVariant(auth()->id(), $product->id, $request->validated());

        return new ProductVariantResource($variant);
    }

    public function show(Product $product, ProductVariant $variant): ProductVariantResource
    {
        Gate::authorize('view', $product);

        if ($variant->product_id !== $product->id) {
            abort(404);
        }

        $variant = $this->variantService->getVariant(auth()->id(), $variant->id);

        return new ProductVariantResource($variant);
    }

    public function update(ProductVariantRequest $request, Product $product, ProductVariant $variant): ProductVariantResource
    {
        Gate::authorize('update', $product);

        if ($variant->product_id !== $product->id) {
            abort(404);
        }

        $variant = $this->variantService->updateVariant(auth()->id(), $variant->id, $request->validated());

        return new ProductVariantResource($variant);
    }

    public function destroy(Product $product, ProductVariant $variant): JsonResponse
    {
        Gate::authorize('delete', $product);

        if ($variant->product_id !== $product->id) {
            abort(404);
        }

        $this->variantService->deleteVariant(auth()->id(), $variant->id);

        return response()->json([
            'message' => 'Product variant deleted successfully.',
        ]);
    }
}
