<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\SellerOrderStatus;
use App\Models\Order;
use App\Models\SellerOrder;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SellerOrder>
 */
class SellerOrderFactory extends Factory
{
    protected $model = SellerOrder::class;

    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 10, 1000);

        return [
            'order_id' => Order::factory(),
            'store_id' => Store::factory(),
            'status' => SellerOrderStatus::PENDING,
            'subtotal' => $subtotal,
            'shipping_amount' => 0,
            'discount_amount' => 0,
            'tax_amount' => 0,
            'commission_amount' => 0,
            'total_amount' => $subtotal,
        ];
    }
}
