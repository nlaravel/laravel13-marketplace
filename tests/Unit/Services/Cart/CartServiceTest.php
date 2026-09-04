<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Cart;

use App\Enums\CartStatus;
use App\Models\User;
use App\Services\Cart\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Inventory;
use App\Models\ProductVariant;
class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_an_active_cart_for_user_without_a_cart(): void
    {
        $user = User::factory()->create();

        $service = new CartService();

        $cart = $service->getCartForUser($user);

        $this->assertDatabaseHas('carts', [
            'id' => $cart->id,
            'user_id' => $user->id,
            'status' => CartStatus::ACTIVE->value,
        ]);

        $this->assertSame($user->id, $cart->user_id);
        $this->assertSame(CartStatus::ACTIVE, $cart->status);
    }

    public function test_it_returns_the_existing_active_cart_for_user(): void
    {
        $user = User::factory()->create();

        $existingCart = $user->carts()->create([
            'status' => CartStatus::ACTIVE,
        ]);

        $service = new CartService();

        $cart = $service->getCartForUser($user);

        $this->assertSame($existingCart->id, $cart->id);

        $this->assertDatabaseCount('carts', 1);

        $this->assertDatabaseHas('carts', [
            'id' => $existingCart->id,
            'user_id' => $user->id,
            'status' => CartStatus::ACTIVE->value,
    ]);
}

    public function test_it_creates_a_new_active_cart_when_user_has_no_active_cart(): void
    {
        $user = User::factory()->create();

        $convertedCart = $user->carts()->create([
            'status' => CartStatus::CONVERTED,
        ]);

        $service = new CartService();

        $cart = $service->getCartForUser($user);

        $this->assertNotSame($convertedCart->id, $cart->id);

        $this->assertSame(CartStatus::ACTIVE, $cart->status);

        $this->assertDatabaseCount('carts', 2);

        $this->assertDatabaseHas('carts', [
            'id' => $convertedCart->id,
            'user_id' => $user->id,
            'status' => CartStatus::CONVERTED->value,
    ]);

    $this->assertDatabaseHas('carts', [
        'id' => $cart->id,
        'user_id' => $user->id,
        'status' => CartStatus::ACTIVE->value,
    ]);
}

    public function test_it_adds_a_new_item_to_the_cart(): void
    {
        $user = User::factory()->create();

        $variant = ProductVariant::factory()->create();

        Inventory::factory()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 10,
            'reserved_quantity' => 0,
        ]);

        $service = new CartService();

        $cartItem = $service->addItem($user, $variant, 3);

        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->id,
            'cart_id' => $cartItem->cart_id,
            'product_variant_id' => $variant->id,
            'quantity' => 3,
        ]);

        $this->assertSame(3, $cartItem->quantity);

        $this->assertDatabaseCount('cart_items', 1);
    }


}