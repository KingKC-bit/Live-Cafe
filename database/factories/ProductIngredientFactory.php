<?php

namespace Database\Factories;

use App\Models\Ingredient;
use App\Models\Product;
use App\Models\ProductIngredient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductIngredient>
 */
class ProductIngredientFactory extends Factory
{
    protected $model = ProductIngredient::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),

            'ingredient_id' => Ingredient::factory(),

            'quantity_required' => fake()->randomFloat(3, 0.001, 1.000),
        ];
    }
}