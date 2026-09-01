<?php

namespace App\Http\Resources\Api\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'label' => $this->label,

            'recipient' => [
                'name' => $this->recipient_name,
                'phone' => $this->phone,
            ],

            'location' => [
                'country' => $this->country,
                'city' => $this->city,
                'area' => $this->area,
                'street' => $this->street,
                'building' => $this->building,
                'apartment' => $this->apartment,
                'address_line' => $this->address_line,
            ],

            'coordinates' => [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ],

            'is_default' => $this->is_default,

            'created_at' => $this->created_at ?->toISOString(),
            'updated_at' => $this->updated_at ?->toISOString(),
        ];
    }
}