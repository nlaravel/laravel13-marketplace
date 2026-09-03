<?php

namespace App\Livewire\Customer;

use App\Exceptions\CartException;
use App\Models\Cart as CartModel;
use App\Models\CartItem;
use App\Services\Cart\CartService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Throwable;

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

    public function increment(CartItem $item): void
    {
        $this->changeQuantity(
            $item,
            $item->quantity + 1
        );
    }

    public function decrement(CartItem $item): void
    {
        if ($item->quantity <= 1) {
            return;
        }

        $this->changeQuantity(
            $item,
            $item->quantity - 1
        );
    }

    public function remove(int $itemId): void
    {
        try {
            $item = CartItem::query()->findOrFail($itemId);

            $this->cartService->removeItem(
                auth()->user(),
                $item
            );

            unset($this->cart);

            $this->dispatch('cart-updated');

            $this->dispatch(
                'success',
                message: 'Item removed from your cart.'
            );
        } catch (CartException $exception) {
            $this->dispatch(
                'error',
                message: $exception->getMessage()
            );
        } catch (Throwable) {
            $this->dispatch(
                'error',
                message: 'Something went wrong. Please try again.'
            );
        }
    }

    public function clearCart(): void
    {
        try {
            $this->cartService->clear(
                auth()->user()
            );

            unset($this->cart);

            $this->dispatch('cart-updated');

            $this->dispatch(
                'success',
                message: 'Your cart has been cleared.'
            );
        } catch (Throwable) {
            $this->dispatch(
                'error',
                message: 'Something went wrong. Please try again.'
            );
        }
    }

    private function changeQuantity(
        CartItem $item,
        int $quantity
    ): void {
        try {
            $this->cartService->updateQuantity(
                auth()->user(),
                $item,
                $quantity
            );

            unset($this->cart);

            $this->dispatch('cart-updated');
        } catch (CartException $exception) {
            $this->dispatch(
                'error',
                message: $exception->getMessage()
            );
        } catch (Throwable) {
            $this->dispatch(
                'error',
                message: 'Something went wrong. Please try again.'
            );
        }
    }

    public function render(): View
    {
        return view('livewire.customer.cart');
    }
}