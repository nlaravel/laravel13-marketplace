<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Seller\ProductRequest;
use App\Http\Resources\Api\Seller\ProductResource;
use App\Models\Product;
use App\Services\Seller\SellerProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class SellerProductController extends Controller
{
    public function __construct(private readonly SellerProductService $productService) {}

    public function index(): AnonymousResourceCollection
    {
        $products = $this->productService->getProducts(auth()->id());

        return ProductResource::collection($products);
    }

    public function store(ProductRequest $request): ProductResource
    {
        Gate::authorize('create', Product::class);

        $validated = $request->validated();

        $product = $this->productService->createProduct(auth()->id(), $validated['store_id'], [
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return new ProductResource($product);
    }

    public function show(Product $product): ProductResource
    {
        Gate::authorize('view', $product);

        $product = $this->productService->getProduct(auth()->id(), $product->id);

        return new ProductResource($product);
    }

    public function update(ProductRequest $request, Product $product): ProductResource
    {
        Gate::authorize('update', $product);

        $validated = $request->validated();

        $product = $this->productService->updateProduct(auth()->id(), $product->id, [
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return new ProductResource($product);
    }

    public function destroy(Product $product): JsonResponse
    {
        Gate::authorize('delete', $product);

        $this->productService->deleteProduct(auth()->id(), $product->id);

        return response()->json([
            'message' => 'Product deleted successfully.',
        ]);
    }
}
