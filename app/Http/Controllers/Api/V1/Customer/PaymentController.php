<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Customer;

use App\Enums\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\PaymentRequest;
use App\Http\Resources\Api\Customer\OrderResource;
use App\Http\Resources\Api\Customer\PaymentResource;
use App\Services\Orders\OrderService;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private readonly OrderService $orderService, private readonly PaymentService $paymentService) {}

    public function store(PaymentRequest $request, int $order): PaymentResource
    {
        $orderModel = $this->orderService->getCustomerOrder($request->user(), $order);

        $payment = $this->paymentService->create($orderModel, $request->enum('method', PaymentMethod::class));

        return new PaymentResource($payment);
    }

    public function confirm(Request $request, int $order): OrderResource
    {
        $orderModel = $this->orderService->getCustomerOrder($request->user(), $order);

        $orderModel = $this->paymentService->confirmOrder($orderModel);

        return new OrderResource($orderModel);
    }
}
