<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\OrderAddressType;
use App\Models\Order;
use App\Models\OrderAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderAddress>
 */
class OrderAddressFactory extends Factory
{
    protected $model = OrderAddress::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'type' => OrderAddressType::SHIPPING,
            'recipient_name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'country' => 'Palestine',
            'city' => fake()->city(),
            'area' => fake()->word(),
            'street' => fake()->streetAddress(),
            'building' => fake()->buildingNumber(),
            'apartment' => fake()->optional()->buildingNumber(),
            'address_line' => fake()->address(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
        ];
    }
}
