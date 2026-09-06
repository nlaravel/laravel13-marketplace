<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Customer\OrderResource;
use App\Services\Orders\OrderService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $orderService) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $orders = $this->orderService->getCustomerOrders($request->user());

        return OrderResource::collection($orders);
    }

    public function show(Request $request, int $order): OrderResource
    {
        $orderModel = $this->orderService->getCustomerOrder($request->user(), $order);

        return new OrderResource($orderModel);
    }

    public function cancel(Request $request, int $order): OrderResource
    {
        $orderModel = $this->orderService->cancelCustomerOrder($request->user(), $order, );

        return new OrderResource($orderModel);
    }
}
