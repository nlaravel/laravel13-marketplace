<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\StoreStatus;
use App\Models\SellerProfile;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Store>
 */
class StoreFactory extends Factory
{
    protected $model = Store::class;

    public function definition(): array
    {
        return [
            'seller_id' => SellerProfile::factory(),
            'name' => fake()->company(),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->paragraph(),
            'status' => StoreStatus::PENDING,
            'approved_at' => null,
            'approved_by' => null,
            'rejection_reason' => null,
        ];
    }
}
