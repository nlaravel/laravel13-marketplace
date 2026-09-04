<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CategoryStatus;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'parent_id' => null,
            'name' => fake()->words(2, true),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->paragraph(),
            'status' => CategoryStatus::ACTIVE,
            'sort_order' => 0,
        ];
    }

    public function child(Category $parent): static
    {
        return $this->state([
            'parent_id' => $parent->id,
        ]);
    }
}