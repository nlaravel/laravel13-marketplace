<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerOrderResource extends JsonResource
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
            'store_id' => $this->store_id,
            'status' => $this->status->value,
            'subtotal' => $this->subtotal,
            'shipping_amount' => $this->shipping_amount,
            'discount_amount' => $this->discount_amount,
            'tax_amount' => $this->tax_amount,
            'commission_amount' => $this->commission_amount,
            'total_amount' => $this->total_amount,

            'items' => OrderItemResource::collection(
                $this->whenLoaded('items')
            ),
        ];
    }
}