<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\CheckoutRequest;
use App\Http\Resources\Api\Customer\OrderResource;
use App\Services\Checkout\CheckoutService;

class CheckoutController extends Controller
{
    public function __construct(private readonly CheckoutService $checkoutService) {}

    public function store(CheckoutRequest $request): OrderResource
    {
        $order = $this->checkoutService->checkout($request->user(), $request->validated('address_id'));

        $order->load([
            'items',
            'sellerOrders.items',
            'addresses',
        ]);

        return new OrderResource($order);
    }
}
