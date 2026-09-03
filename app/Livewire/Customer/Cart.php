<?php

namespace App\Livewire\Customer;

use App\Models\Cart as CartModel;
use App\Services\Cart\CartService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.customer-layout')]
class Cart extends Component
{
private CartService $cartService;

    public function boot(CartService $cartService): void
    {
        $this->cartService = $cartService;
    }

    #[Computed]
    public function cart(): CartModel
    {
        return $this->cartService->getCartForUser(
            auth()->user()
        );
    }

    public function render(): View
    {
        return view('livewire.customer.cart');
    }
}
