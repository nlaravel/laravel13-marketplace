<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Customer\OrderResource;
use App\Services\Checkout\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CheckoutService $checkoutService
    ) {}

public function store(Request $request): OrderResource
{
    $order = $this->checkoutService->checkout($request->user());

    $order->load([
        'items',
        'sellerOrders.items',
        'addresses',
    ]);

    return new OrderResource($order);
}
}