<?php

declare(strict_types=1);

namespace App\Livewire\Customer\Checkout;

use App\Models\Cart;
use App\Services\Cart\CartService;
use App\Services\Checkout\CheckoutService;
use App\Services\Customer\AddressService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.customer-layout')]
class Index extends Component
{
    public ?int $addressId = null;

    private CartService $cartService;

    private AddressService $addressService;

    private CheckoutService $checkoutService;

    public function boot(CartService $cartService, AddressService $addressService, CheckoutService $checkoutService): void
    {
        $this->cartService = $cartService;
        $this->addressService = $addressService;
        $this->checkoutService = $checkoutService;
    }

    public function mount(): void
    {
        $defaultAddress = $this->addressService
            ->getAddresses(auth()->user())
            ->firstWhere('is_default', true);

        $this->addressId = $defaultAddress?->id;
    }

    #[Computed]
    public function cart(): Cart
    {
        return $this->cartService->getCartForUser(auth()->user());
    }

    #[Computed]
    public function addresses(): Collection
    {
        return $this->addressService->getAddresses(auth()->user());
    }

    #[Computed]
    public function subtotal(): float
    {
        return (float) $this->cart->items->sum(fn ($item) => $item->quantity * $item->productVariant->price);
    }

    public function placeOrder(): void
    {
        if ($this->cart->items->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => 'Your cart is empty.',
            ]);
        }

        if ($this->addressId === null) {
            throw ValidationException::withMessages([
                'addressId' => 'Please select a delivery address.',
            ]);
        }

        $order = $this->checkoutService->checkout(auth()->user(), $this->addressId);

        session()->flash('checkout_success', 'Order placed successfully.');

        $this->redirectRoute('customer.orders.show', ['order' => $order->id]);
    }

    public function render(): View
    {
        return view('livewire.customer.checkout.index');
    }
}
