<?php

namespace App\Services\Cart;

use App\Enums\CartStatus;
use App\Enums\ProductStatus;
use App\Exceptions\CartException;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function getActiveCart(User $user): Cart
    {
        return Cart::firstOrCreate([
            'user_id' => $user->id,
            'status' => CartStatus::ACTIVE,
        ]);
    }

    public function addItem(User $user, ProductVariant $variant, int $quantity): CartItem
    {
        if ($quantity <= 0) {
            throw new CartException(
                'Quantity must be greater than zero.'
            );
        }

        $this->validateVariant($variant);

        return DB::transaction(function () use (
            $user,
            $variant,
            $quantity
        ): CartItem {
            return $this->addItemWithoutTransaction(
                $user,
                $variant,
                $quantity
            );
        });
    }

    public function addItems(User $user, array $items): Cart
    {
        return DB::transaction(function () use (
            $user,
            $items
        ): Cart {
            foreach ($items as $item) {
                $variant = ProductVariant::query()
                    ->findOrFail($item['product_variant_id']);

                if ($item['quantity'] <= 0) {
                    throw new CartException(
                        'Quantity must be greater than zero.'
                    );
                }

                $this->validateVariant($variant);

                $this->addItemWithoutTransaction(
                    $user,
                    $variant,
                    $item['quantity']
                );
            }

            return $this->getCartForUser($user);
        });
    }

    public function updateQuantity(User $user, CartItem $item, int $quantity): CartItem
    {
        if ($quantity < 1) {
            throw new CartException(
                'Quantity must be at least 1.'
            );
        }

        $this->ensureCartOwnership($user, $item);

        return DB::transaction(function () use (
            $item,
            $quantity
        ): CartItem {
            $inventory = $item->productVariant
                ->inventory()
                ->lockForUpdate()
                ->first();

            if (! $inventory) {
                throw new CartException(
                    'Inventory not found.'
                );
            }

            $availableQuantity =
                $inventory->quantity - $inventory->reserved_quantity;

            if ($quantity > $availableQuantity) {
                throw new CartException(
                    'Insufficient stock.'
                );
            }

            $item->update([
                'quantity' => $quantity,
            ]);

            return $item->refresh();
        });
    }

    public function removeItem(User $user, CartItem $item): void
    {
        $this->ensureCartOwnership($user, $item);

        $item->delete();
    }

    public function clear(User $user): void
    {
        $cart = $this->getActiveCart($user);

        $cart->items()->delete();
    }

    public function getCartForUser(User $user): Cart
    {
        return $this->getActiveCart($user)
            ->load([
                'items.productVariant.product',
                'items.productVariant.inventory',
            ]);
    }

    private function addItemWithoutTransaction(User $user, ProductVariant $variant, int $quantity): CartItem
    {
        $inventory = $variant->inventory()
            ->lockForUpdate()
            ->first();

        if (! $inventory) {
            throw new CartException(
                'Inventory not found.'
            );
        }

        $availableQuantity =
            $inventory->quantity - $inventory->reserved_quantity;

        $cart = $this->getActiveCart($user);

        $item = $cart->items()
            ->where('product_variant_id', $variant->id)
            ->lockForUpdate()
            ->first();

        $newQuantity = $item
            ? $item->quantity + $quantity
            : $quantity;

        if ($newQuantity > $availableQuantity) {
            throw new CartException(
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
    }

    private function validateVariant(ProductVariant $variant): void
    {
        $variant->loadMissing('product');

        if (! $variant->is_active) {
            throw new CartException(
                'This product variant is not available.'
            );
        }

        if (! $variant->product) {
            throw new CartException(
                'The product does not exist.'
            );
        }

        if ($variant->product->status !== ProductStatus::ACTIVE) {
            throw new CartException(
                'This product is not available.'
            );
        }
    }

    private function ensureCartOwnership(User $user, CartItem $item): void
    {
        $item->loadMissing('cart');

        if (! $item->cart) {
            throw new CartException(
                'Cart not found.'
            );
        }

        if ($item->cart->user_id !== $user->id) {
            throw new CartException(
                'This cart item does not belong to the user.'
            );
        }

        if ($item->cart->status !== CartStatus::ACTIVE) {
            throw new CartException(
                'This cart is not active.'
            );
        }
    }
}