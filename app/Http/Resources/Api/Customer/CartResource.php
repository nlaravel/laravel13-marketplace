<?php

namespace App\Http\Resources\Api\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $items = $this->whenLoaded('items');

        $itemsCount = $items
            ? $items->sum('quantity')
            : 0;

        $subtotal = $items
            ? $items->sum(function ($item) {
                $unitPrice = (float) $item->productVariant->price;

                return $unitPrice * $item->quantity;
            })
            : 0;

        return [
            'id' => $this->id,
            'status' => $this->status->value,

            'items' => CartItemResource::collection(
                $this->whenLoaded('items')
            ),

            'items_count' => $itemsCount,

            'subtotal' => number_format(
                $subtotal,
                2,
                '.',
                ''
            ),
        ];
    }
}