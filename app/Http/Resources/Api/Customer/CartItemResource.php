<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $unitPrice = (float) $this->productVariant->price;
        $quantity = (int) $this->quantity;

        return [
            'id' => $this->id,
            'quantity' => $quantity,
            'unit_price' => number_format($unitPrice, 2, '.', ''),
            'subtotal' => number_format($unitPrice * $quantity, 2, '.', ''),

            'product_variant' => [
                'id' => $this->productVariant->id,
                'sku' => $this->productVariant->sku,
                'price' => number_format($unitPrice, 2, '.', ''),

                'product' => [
                    'id' => $this->productVariant->product->id,
                    'name' => $this->productVariant->product->name,
                    'slug' => $this->productVariant->product->slug,
                ],
            ],
        ];
    }
}
