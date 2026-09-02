<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\AddCartItemRequest;
use App\Http\Requests\Api\V1\Customer\UpdateCartItemRequest;
use App\Http\Resources\Api\Customer\CartResource;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Services\Cart\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService
    ) {
    }

public function show(Request $request): CartResource
{
    $cart = $this->cartService->getActiveCart(
        $request->user()
    );

    $cart->load([
        'items.productVariant.product',
    ]);

    return new CartResource($cart);
}

public function store(AddCartItemRequest $request): CartResource
{
    $variant = ProductVariant::query()
        ->findOrFail(
            $request->integer('product_variant_id')
        );

    $this->cartService->addItem(
        $request->user(),
        $variant,
        $request->integer('quantity')
    );

    $cart = $this->cartService->getActiveCart(
        $request->user()
    );

    $cart->load([
        'items.productVariant.product',
    ]);

    return new CartResource($cart);
}

public function update(
    UpdateCartItemRequest $request,
    CartItem $item
): CartResource {
    $this->cartService->updateQuantity(
        $request->user(),
        $item,
        $request->integer('quantity')
    );

    $cart = $this->cartService->getActiveCart(
        $request->user()
    );

    $cart->load([
        'items.productVariant.product',
    ]);

    return new CartResource($cart);
}

public function destroyItem(
    Request $request,
    CartItem $item
): CartResource {
    $this->cartService->removeItem(
        $request->user(),
        $item
    );

    $cart = $this->cartService->getActiveCart(
        $request->user()
    );

    $cart->load([
        'items.productVariant.product',
    ]);

    return new CartResource($cart);
}

public function clear(Request $request): JsonResponse
{
    $this->cartService->clear(
        $request->user()
    );

    return response()->json([
        'message' => 'Cart cleared successfully.',
    ]);
}
}