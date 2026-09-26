<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SalesDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SalesDetail>
 */
class SalesDetailFactory extends Factory
{
    protected $model = SalesDetail::class;

    public function definition(): array
    {
        return [
            'sales_id' => Sale::factory(),

            'product_id' => Product::factory(),

            'quantity' => fake()->numberBetween(1, 5),

            'unit_price' => fake()->randomFloat(2, 15, 400),
        ];
    }
}