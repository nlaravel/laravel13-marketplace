<?php

namespace App\Services\Cart;

use App\Enums\CartStatus;
use App\Enums\ProductStatus;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CartService
{
    public function getActiveCart(User $user): Cart
    {
        return Cart::firstOrCreate([
            'user_id' => $user->id,
            'status' => CartStatus::ACTIVE,
        ]);
    }

    public function addItem(
        User $user,
        ProductVariant $variant,
        int $quantity
    ): CartItem {
        if ($quantity <= 0) {
            throw new InvalidArgumentException(
                'Quantity must be greater than zero.'
            );
        }

        $this->validateVariant($variant);

        return DB::transaction(function () use (
            $user,
            $variant,
            $quantity
        ) {
            $cart = $this->getActiveCart($user);

            $item = $cart->items()
                ->where('product_variant_id', $variant->id)
                ->lockForUpdate()
                ->first();

            $inventory = $variant->inventory;

            if (! $inventory) {
                throw new InvalidArgumentException(
                    'Inventory not found.'
                );
            }

            $newQuantity = $quantity;

            if ($item) {
                $newQuantity = $item->quantity + $quantity;
            }

            if ($newQuantity > $inventory->available_quantity) {
                throw new InvalidArgumentException(
                    'Insufficient stock.'
                );
            }

            if ($item) {
                $item->update([
                    'quantity' => $newQuantity,
                ]);

                return $item->refresh();
            }

            return $cart->items()->create([
                'product_variant_id' => $variant->id,
                'quantity' => $quantity,
            ]);
        });
    }

    public function updateQuantity(
        User $user,
        CartItem $item,
        int $quantity
    ): CartItem {
        if ($quantity < 1) {
            throw new InvalidArgumentException(
                'Quantity must be at least 1.'
            );
        }

        $this->ensureCartOwnership($user, $item);

        $item->loadMissing('productVariant.inventory');

        $inventory = $item->productVariant->inventory;

        if (! $inventory) {
            throw new InvalidArgumentException(
                'Inventory not found.'
            );
        }

        if ($quantity > $inventory->available_quantity) {
            throw new InvalidArgumentException(
                'Insufficient stock.'
            );
        }

        $item->update([
            'quantity' => $quantity,
        ]);

        return $item->refresh();
    }

    public function removeItem(
        User $user,
        CartItem $item
    ): void {
        $this->ensureCartOwnership($user, $item);

        $item->delete();
    }

    public function clear(User $user): void
    {
        $cart = $this->getActiveCart($user);

        $cart->items()->delete();
    }

    private function validateVariant(
        ProductVariant $variant
    ): void {
        if (! $variant->is_active) {
            throw new InvalidArgumentException(
                'This product variant is not available.'
            );
        }

        if (! $variant->product) {
            throw new InvalidArgumentException(
                'The product does not exist.'
            );
        }

        if ($variant->product->status !== ProductStatus::ACTIVE) {
            throw new InvalidArgumentException(
                'This product is not available.'
            );
        }
    }

    private function ensureCartOwnership(
        User $user,
        CartItem $item
    ): void {
        $item->loadMissing('cart');

        if (! $item->cart) {
            throw new InvalidArgumentException(
                'Cart not found.'
            );
        }

        if ($item->cart->user_id !== $user->id) {
            throw new InvalidArgumentException(
                'This cart item does not belong to the user.'
            );
        }

        if ($item->cart->status !== CartStatus::ACTIVE) {
            throw new InvalidArgumentException(
                'This cart is not active.'
            );
        }
    }
}