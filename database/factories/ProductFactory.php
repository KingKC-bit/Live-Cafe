<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),

            'price' => fake()->randomFloat(2, 15, 400),

            'category_id' => Category::factory(),

            'description' => fake()->sentence(12),

            'prod_availability' => true,

            'quantity' => fake()->numberBetween(5, 100),
        ];
    }

    public function unavailable(): static
    {
        return $this->state(fn () => [
            'prod_availability' => false,
        ]);
    }

    public function soldOut(): static
    {
        return $this->state(fn () => [
            'prod_availability' => false,
            'quantity' => 0,
        ]);
    }
}
