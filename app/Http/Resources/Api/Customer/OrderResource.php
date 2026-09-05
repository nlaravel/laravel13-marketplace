<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'status' => $this->status->value,
            'subtotal' => $this->subtotal,
            'shipping_amount' => $this->shipping_amount,
            'discount_amount' => $this->discount_amount,
            'tax_amount' => $this->tax_amount,
            'total_amount' => $this->total_amount,
            'currency' => $this->currency,

            'items' => OrderItemResource::collection(
                $this->whenLoaded('items')
            ),

            'seller_orders' => SellerOrderResource::collection(
                $this->whenLoaded('sellerOrders')
            ),

            'addresses' => OrderAddressResource::collection(
                $this->whenLoaded('addresses')
            ),

            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}