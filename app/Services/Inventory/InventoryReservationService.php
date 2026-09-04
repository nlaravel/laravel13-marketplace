<?php

declare(strict_types=1);

namespace App\Services\Inventory;

use App\Enums\InventoryTransactionType;
use App\Models\Cart;
use App\Models\Inventory;
use App\Models\Order;
use InvalidArgumentException;

class InventoryReservationService
{
    public function reserve(Cart $cart, Order $order): void
    {
        foreach ($cart->items as $item) {
            $inventory = Inventory::where('product_variant_id', $item->product_variant_id)
                ->lockForUpdate()
                ->first();

            if (! $inventory) {
                throw new InvalidArgumentException("Inventory not found for variant {$item->product_variant_id}.");
            }

            $availableQuantity
                = $inventory->quantity - $inventory->reserved_quantity;

            if ($item->quantity > $availableQuantity) {
                throw new InvalidArgumentException("Insufficient stock for variant {$item->product_variant_id}.");
            }

            $quantityBefore = $inventory->reserved_quantity;

            $inventory->increment('reserved_quantity', $item->quantity);

            $inventory->refresh();

            $inventory->transactions()->create([
                'type' => InventoryTransactionType::RESERVATION,
                'quantity' => $item->quantity,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $inventory->reserved_quantity,
                'reference_type' => Order::class,
                'reference_id' => $order->id,
                'created_by' => $order->customer_id,
                'note' => 'Inventory reserved for order.',
            ]);
        }
    }

    public function release(Order $order): void
    {
        $items = $order->items()->get();

        foreach ($items as $item) {
            $alreadyReleased = $item->productVariant
                ->inventory
                ->transactions()
                ->where('type', InventoryTransactionType::RELEASE)
                ->where('reference_type', Order::class)
                ->where('reference_id', $order->id)
                ->exists();

            if ($alreadyReleased) {
                continue;
            }

            $inventory = Inventory::where('product_variant_id', $item->product_variant_id)
                ->lockForUpdate()
                ->first();

            if (! $inventory) {
                throw new InvalidArgumentException("Inventory not found for variant {$item->product_variant_id}.");
            }

            if ($inventory->reserved_quantity < $item->quantity) {
                throw new InvalidArgumentException("Insufficient reserved quantity for variant {$item->product_variant_id}.");
            }

            $quantityBefore = $inventory->reserved_quantity;

            $inventory->decrement('reserved_quantity', $item->quantity);

            $inventory->refresh();

            $inventory->transactions()->create([
                'type' => InventoryTransactionType::RELEASE,
                'quantity' => $item->quantity,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $inventory->reserved_quantity,
                'reference_type' => Order::class,
                'reference_id' => $order->id,
                'created_by' => $order->customer_id,
                'note' => 'Inventory reservation released for order.',
            ]);
        }
    }
}
