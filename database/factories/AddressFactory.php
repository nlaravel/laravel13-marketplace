<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Address>
 */
class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'label' => fake()->randomElement([
                'Home',
                'Work',
                'Other',
            ]),
            'recipient_name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'country' => 'Palestine',
            'city' => fake()->city(),
            'area' => fake()->streetName(),
            'street' => fake()->streetName(),
            'building' => fake()->buildingNumber(),
            'apartment' => (string) fake()->numberBetween(1, 20),
            'address_line' => fake()->address(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'is_default' => false,
        ];
    }
}
