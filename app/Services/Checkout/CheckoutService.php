<?php

namespace App\Services\Checkout;
use App\Models\SellerOrder;
use App\Enums\CartStatus;
use App\Enums\OrderStatus;
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use App\Services\Inventory\InventoryReservationService;
class CheckoutService
{

    public function __construct(
    private InventoryReservationService $inventoryReservationService
) {
}

    public function checkout(User $customer): Order
    {
        return DB::transaction(function () use ($customer) {
            $cart = $this->getActiveCart($customer);

            $this->validateCart($cart);

            $order = $this->createOrder($customer, $cart);

            $this->createSellerOrders($order, $cart);
            $this->createOrderAddresses($order, $customer);
            $this->inventoryReservationService->reserve($cart, $order);
            $this->clearCart($cart);
            return $order;
        });
    }

    private function getActiveCart(User $customer): Cart
    {
        $cart = $customer->carts()
            ->where('status', CartStatus::ACTIVE)
            ->with([
                'items.productVariant.product.store',
                'items.productVariant.inventory',
            ])
            ->first();

        if (! $cart) {
            throw new InvalidArgumentException(
                'Active cart not found.'
            );
        }

        return $cart;
    }

    private function validateCart(Cart $cart): void
    {
        if ($cart->items->isEmpty()) {
            throw new InvalidArgumentException(
                'Cart is empty.'
            );
        }

        foreach ($cart->items as $item) {
            $variant = $item->productVariant;

            if (! $variant) {
                throw new InvalidArgumentException(
                    'Product variant not found.'
                );
            }

            if (! $variant->is_active) {
                throw new InvalidArgumentException(
                    "Product variant {$variant->id} is inactive."
                );
            }

            $product = $variant->product;

            if (! $product) {
                throw new InvalidArgumentException(
                    'Product not found.'
                );
            }

            if (! $product->store) {
                throw new InvalidArgumentException(
                    'Store not found.'
                );
            }

            $inventory = $variant->inventory;

            if (! $inventory) {
                throw new InvalidArgumentException(
                    "Inventory not found for variant {$variant->id}."
                );
            }

            if ($item->quantity > $inventory->available_quantity) {
                throw new InvalidArgumentException(
                    "Insufficient stock for variant {$variant->id}."
                );
            }
        }
    }

    private function createOrder(User $customer, Cart $cart): Order
    {
        $subtotal = $cart->items->sum(function ($item) {
            return $item->productVariant->price * $item->quantity;
        });

        return Order::create([
            'customer_id' => $customer->id,
            'order_number' => $this->generateOrderNumber(),
            'status' => OrderStatus::PENDING,
            'subtotal' => $subtotal,
            'shipping_amount' => 0,
            'discount_amount' => 0,
            'tax_amount' => 0,
            'total_amount' => $subtotal,
            'currency' => 'USD',
        ]);
    }

    private function createSellerOrders(Order $order, Cart $cart): void
    {
        $groups = $cart->items->groupBy(function ($item) {
            return $item->productVariant->product->store_id;
        });

        foreach ($groups as $storeId => $items) {
            $subtotal = $items->sum(function ($item) {
                return $item->productVariant->price * $item->quantity;
            });

            $sellerOrder = $order->sellerOrders()->create([
                'store_id' => $storeId,
                'status' => \App\Enums\SellerOrderStatus::PENDING,
                'subtotal' => $subtotal,
                'shipping_amount' => 0,
                'discount_amount' => 0,
                'tax_amount' => 0,
                'commission_amount' => 0,
                'total_amount' => $subtotal,
            ]);

            $this->createOrderItems($order, $sellerOrder, $items);
        }
    }

    private function createOrderItems(
        Order $order,
        SellerOrder $sellerOrder,
        $items
    ): void {
        foreach ($items as $item) {
            $variant = $item->productVariant;
            $product = $variant->product;

            $unitPrice = $variant->price;
            $totalAmount = $unitPrice * $item->quantity;

            $sellerOrder->items()->create([
                'order_id' => $order->id,
                'seller_order_id' => $sellerOrder->id,
                'product_id' => $product->id,
                'product_variant_id' => $variant->id,
                'product_name' => $product->name,
                'sku' => $variant->sku,
                'quantity' => $item->quantity,
                'unit_price' => $unitPrice,
                'discount_amount' => 0,
                'tax_amount' => 0,
                'total_amount' => $totalAmount,
            ]);
        }
    }
    private function createOrderAddresses(
        Order $order,
        User $customer
    ): void {
        $address = $customer->addresses()
            ->where('is_default', true)
            ->first();

        if (! $address) {
            throw new InvalidArgumentException(
                'Default address not found.'
            );
        }

        $order->addresses()->create([
            'type' => \App\Enums\OrderAddressType::SHIPPING,
            'recipient_name' => $address->recipient_name,
            'phone' => $address->phone,
            'country' => $address->country,
            'city' => $address->city,
            'area' => $address->area,
            'street' => $address->street,
            'building' => $address->building,
            'apartment' => $address->apartment,
            'address_line' => $address->address_line,
            'latitude' => $address->latitude,
            'longitude' => $address->longitude,
        ]);
    }


    private function clearCart(Cart $cart): void
    {
        $cart->items()->delete();
    }

    private function generateOrderNumber(): string
    {
        return 'ORD-' . now()->format('YmdHis') . '-' . random_int(1000, 9999);
    }
}