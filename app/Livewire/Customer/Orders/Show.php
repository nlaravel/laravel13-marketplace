<?php

declare(strict_types=1);

namespace App\Livewire\Customer\Orders;

use App\Enums\PaymentMethod;
use App\Models\Order;
use App\Models\Payment;
use App\Services\Orders\OrderService;
use App\Services\Payment\PaymentService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.customer-layout')]
class Show extends Component
{
    private OrderService $orderService;

    private PaymentService $paymentService;

    public int $orderId;

    public string $paymentMethod = PaymentMethod::CARD->value;

    public function boot(
        OrderService $orderService,
        PaymentService $paymentService
    ): void {
        $this->orderService = $orderService;
        $this->paymentService = $paymentService;
    }

    public function mount(int $order): void
    {
        $this->orderId = $order;

        if (session()->has('checkout_success')) {
            $this->dispatch(
                'show-success',
                message: session()->pull('checkout_success')
            );
        }
    }

    #[Computed]
    public function order(): Order
    {
        return $this->orderService->getCustomerOrder(
            auth()->user(),
            $this->orderId
        );
    }

    #[Computed]
    public function payment(): ?Payment
    {
        return $this->paymentService->getLatestForOrder($this->order);
    }

    public function pay(): void
    {
        $paymentMethod = PaymentMethod::tryFrom($this->paymentMethod);

        if ($paymentMethod === null) {
            $this->addError(
                'paymentMethod',
                'Please select a valid payment method.'
            );

            return;
        }

        $this->paymentService->create(
            $this->order,
            $paymentMethod
        );

        $this->dispatch(
            'show-success',
            message: 'Payment completed successfully.'
        );
    }

    public function confirmPayment(): void
    {
        $this->paymentService->confirmOrder($this->order);

        $this->dispatch(
            'show-success',
            message: 'Order confirmed successfully.'
        );
    }

    public function cancel(): void
    {
        $this->orderService->cancelCustomerOrder(
            auth()->user(),
            $this->orderId
        );

        $this->dispatch(
            'show-success',
            message: 'Order cancelled successfully.'
        );
    }

    public function render(): View
    {
        return view('livewire.customer.orders.show');
    }
}
