<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Cart;
use App\Exceptions\CartException;
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


    public function test_it_updates_cart_item_quantity(): void
    {
        $user = User::factory()->create();
        $variant = ProductVariant::factory()->create();

        Inventory::factory()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 10,
            'reserved_quantity' => 2,
        ]);

        $cart = $user->carts()->create([
            'status' => CartStatus::ACTIVE,
        ]);

        $item = $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $service = new CartService();

        $updatedItem = $service->updateQuantity($user, $item, 5);

        $this->assertSame(5, $updatedItem->quantity);

        $this->assertDatabaseHas('cart_items', [
            'id' => $item->id,
            'quantity' => 5,
        ]);
    }

    public function test_it_rejects_zero_quantity_when_updating_cart_item(): void
    {
        $user = User::factory()->create();
        $variant = ProductVariant::factory()->create();

        Inventory::factory()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 10,
            'reserved_quantity' => 0,
        ]);

        $cart = $user->carts()->create([
            'status' => CartStatus::ACTIVE,
        ]);

        $item = $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $service = new CartService();

        $this->expectException(CartException::class);

        $service->updateQuantity($user, $item, 0);
    }

    public function test_it_rejects_quantity_greater_than_available_stock(): void
    {
        $user = User::factory()->create();
        $variant = ProductVariant::factory()->create();

        Inventory::factory()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 10,
            'reserved_quantity' => 2,
        ]);

        $cart = $user->carts()->create([
            'status' => CartStatus::ACTIVE,
        ]);

        $item = $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $service = new CartService();

        $this->expectException(CartException::class);

        $service->updateQuantity($user, $item, 9);
    }

    public function test_it_updates_quantity_to_exact_available_stock(): void
    {
        $user = User::factory()->create();
        $variant = ProductVariant::factory()->create();

        Inventory::factory()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 10,
            'reserved_quantity' => 2,
        ]);

        $cart = $user->carts()->create([
            'status' => CartStatus::ACTIVE,
        ]);

        $item = $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $service = new CartService();

        $updatedItem = $service->updateQuantity($user, $item, 8);

        $this->assertSame(8, $updatedItem->quantity);

        $this->assertDatabaseHas('cart_items', [
            'id' => $item->id,
            'quantity' => 8,
        ]);
    }

    public function test_it_rejects_cart_item_belonging_to_another_user(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $variant = ProductVariant::factory()->create();

        Inventory::factory()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 10,
            'reserved_quantity' => 0,
        ]);

        $cart = $owner->carts()->create([
            'status' => CartStatus::ACTIVE,
        ]);

        $item = $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $service = new CartService();

        $this->expectException(CartException::class);

        $service->updateQuantity($otherUser, $item, 3);
    }

    public function test_it_rejects_update_when_cart_is_not_active(): void
    {
        $user = User::factory()->create();
        $variant = ProductVariant::factory()->create();

        Inventory::factory()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 10,
            'reserved_quantity' => 0,
        ]);

        $cart = $user->carts()->create([
            'status' => CartStatus::CONVERTED,
        ]);

        $item = $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $service = new CartService();

        $this->expectException(CartException::class);

        $service->updateQuantity($user, $item, 3);
    }

    public function test_it_rejects_update_when_inventory_does_not_exist(): void
    {
        $user = User::factory()->create();
        $variant = ProductVariant::factory()->create();

        $cart = $user->carts()->create([
            'status' => CartStatus::ACTIVE,
        ]);

        $item = $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $service = new CartService();

        $this->expectException(CartException::class);

        $service->updateQuantity($user, $item, 3);
    }

    public function test_it_removes_cart_item_belonging_to_user(): void
    {
        $user = User::factory()->create();
        $variant = ProductVariant::factory()->create();

        $cart = $user->carts()->create([
            'status' => CartStatus::ACTIVE,
        ]);

        $item = $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $service = new CartService();

        $service->removeItem($user, $item);

        $this->assertDatabaseMissing('cart_items', [
            'id' => $item->id,
        ]);
    }

    public function test_it_rejects_removing_cart_item_belonging_to_another_user(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $variant = ProductVariant::factory()->create();

        $cart = $owner->carts()->create([
            'status' => CartStatus::ACTIVE,
        ]);

        $item = $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $service = new CartService();

        $this->expectException(CartException::class);

        $service->removeItem($otherUser, $item);
    }

    public function test_it_rejects_removing_item_from_inactive_cart(): void
    {
        $user = User::factory()->create();
        $variant = ProductVariant::factory()->create();

        $cart = $user->carts()->create([
            'status' => CartStatus::CONVERTED,
        ]);

        $item = $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $service = new CartService();

        $this->expectException(CartException::class);

        $service->removeItem($user, $item);
    }

    public function test_it_clears_all_items_from_cart(): void
    {
        $user = User::factory()->create();
        $variant1 = ProductVariant::factory()->create();
        $variant2 = ProductVariant::factory()->create();

        $cart = $user->carts()->create([
            'status' => CartStatus::ACTIVE,
        ]);

        $item1 = $cart->items()->create([
            'product_variant_id' => $variant1->id,
            'quantity' => 2,
        ]);

        $item2 = $cart->items()->create([
            'product_variant_id' => $variant2->id,
            'quantity' => 3,
        ]);

        $service = new CartService();

        $service->clear($user);

        $this->assertDatabaseMissing('cart_items', [
            'id' => $item1->id,
        ]);

        $this->assertDatabaseMissing('cart_items', [
            'id' => $item2->id,
        ]);
    }

    public function test_it_clears_empty_cart_without_error(): void
    {
        $user = User::factory()->create();

        $user->carts()->create([
            'status' => CartStatus::ACTIVE,
        ]);

        $service = new CartService();

        $service->clear($user);

        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_it_creates_active_cart_when_clearing_without_existing_cart(): void
    {
        $user = User::factory()->create();

        $service = new CartService();

        $service->clear($user);

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'status' => CartStatus::ACTIVE,
        ]);

        $cart = $user->carts()
            ->where('status', CartStatus::ACTIVE)
            ->first();

        $this->assertNotNull($cart);
        $this->assertSame(0, $cart->items()->count());
    }








}