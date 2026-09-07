<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 10, 500);

        return [
            'customer_id' => User::factory(),
            'order_number' => 'ORD-'.fake()->unique()->numerify('##########'),
            'status' => OrderStatus::PENDING,
            'subtotal' => $subtotal,
            'shipping_amount' => 0,
            'discount_amount' => 0,
            'tax_amount' => 0,
            'total_amount' => $subtotal,
            'currency' => 'USD',
            'notes' => null,
            'confirmed_at' => null,
            'cancelled_at' => null,
            'completed_at' => null,
        ];
    }
}
