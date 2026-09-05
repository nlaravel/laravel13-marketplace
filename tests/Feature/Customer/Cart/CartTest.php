<?php

declare(strict_types=1);

namespace Tests\Feature\Customer\Cart;

use App\Enums\CartStatus;
use App\Livewire\Customer\Cart;
use App\Models\Inventory;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_items_are_displayed_for_customer(): void
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

        $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $this->actingAs($user);

        Livewire::test(Cart::class)
            ->assertSee($variant->product->name)
            ->assertSee('2');
    }

    public function test_empty_cart_displays_empty_state(): void
    {
        $user = User::factory()->create();

        $user->carts()->create([
            'status' => CartStatus::ACTIVE,
        ]);

        $this->actingAs($user);

        Livewire::test(Cart::class)
            ->assertSee('Your cart is empty');
        // ->assertSee("You haven't added any products to your cart yet.");
    }

    public function test_increment_increases_item_quantity_when_stock_is_available(): void
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

        $this->actingAs($user);

        Livewire::test(Cart::class)
            ->call('increment', $item)
            ->assertDispatched('cart-updated');

        $this->assertDatabaseHas('cart_items', [
            'id' => $item->id,
            'quantity' => 3,
        ]);
    }

    public function test_increment_does_not_change_quantity_when_stock_is_exceeded(): void
    {
        $user = User::factory()->create();

        $variant = ProductVariant::factory()->create();

        Inventory::factory()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 3,
            'reserved_quantity' => 0,
        ]);

        $cart = $user->carts()->create([
            'status' => CartStatus::ACTIVE,
        ]);

        $item = $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 3,
        ]);

        $this->actingAs($user);

        Livewire::test(Cart::class)
            ->call('increment', $item)
            ->assertDispatched('error', message: 'Insufficient stock.')
            ->assertNotDispatched('cart-updated');

        $this->assertDatabaseHas('cart_items', [
            'id' => $item->id,
            'quantity' => 3,
        ]);
    }

    public function test_decrement_decreases_item_quantity_when_quantity_is_greater_than_one(): void
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
            'quantity' => 3,
        ]);

        $this->actingAs($user);

        Livewire::test(Cart::class)
            ->call('decrement', $item)
            ->assertDispatched('cart-updated');

        $this->assertDatabaseHas('cart_items', [
            'id' => $item->id,
            'quantity' => 2,
        ]);
    }

    public function test_decrement_does_nothing_when_quantity_is_one(): void
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
            'quantity' => 1,
        ]);

        $this->actingAs($user);

        Livewire::test(Cart::class)
            ->call('decrement', $item)
            ->assertNotDispatched('cart-updated')
            ->assertNotDispatched('error');

        $this->assertDatabaseHas('cart_items', [
            'id' => $item->id,
            'quantity' => 1,
        ]);
    }


    public function test_increment_rejects_item_belonging_to_another_user(): void
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

        $this->actingAs($otherUser);

        Livewire::test(Cart::class)
            ->call('increment', $item)
            ->assertDispatched('error', message: 'This cart item does not belong to the user.')
            ->assertNotDispatched('cart-updated');

        $this->assertDatabaseHas('cart_items', [
            'id' => $item->id,
            'quantity' => 2,
        ]);
    }

    public function test_decrement_rejects_item_belonging_to_another_user(): void
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
            'quantity' => 3,
        ]);

        $this->actingAs($otherUser);

        Livewire::test(Cart::class)
            ->call('decrement', $item)
            ->assertDispatched('error', message: 'This cart item does not belong to the user.')
            ->assertNotDispatched('cart-updated');

        $this->assertDatabaseHas('cart_items', [
            'id' => $item->id,
            'quantity' => 3,
        ]);
    }



    public function test_remove_deletes_item_and_dispatches_events(): void
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

        $this->actingAs($user);

        Livewire::test(Cart::class)
            ->call('remove', $item->id)
            ->assertDispatched('cart-updated')
            ->assertDispatched('success', message: 'Item removed from your cart.');

        $this->assertDatabaseMissing('cart_items', [
            'id' => $item->id,
        ]);
    }

    public function test_remove_handles_non_existing_item_without_breaking_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(Cart::class)
            ->call('remove', 999999)
            ->assertDispatched('error', message: 'Something went wrong. Please try again.');
    }

    public function test_remove_rejects_item_belonging_to_another_user(): void
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

        $this->actingAs($otherUser);

        Livewire::test(Cart::class)
            ->call('remove', $item->id)
            ->assertDispatched('error', message: 'This cart item does not belong to the user.')
            ->assertNotDispatched('cart-updated');

        $this->assertDatabaseHas('cart_items', [
            'id' => $item->id,
            'quantity' => 2,
        ]);
    }

    public function test_clear_cart_removes_all_items_and_dispatches_events(): void
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

        $this->actingAs($user);

        Livewire::test(Cart::class)
            ->call('clearCart')
            ->assertDispatched('cart-updated')
            ->assertDispatched('success', message: 'Your cart has been cleared.');

        $this->assertDatabaseMissing('cart_items', [
            'id' => $item1->id,
        ]);

        $this->assertDatabaseMissing('cart_items', [
            'id' => $item2->id,
        ]);
    }
}
