<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderDetail>
 */
class OrderDetailFactory extends Factory
{
    protected $model = OrderDetail::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),

            'product_id' => Product::factory(),

            'quantity' => fake()->numberBetween(1, 5),

            'unit_price' => fake()->randomFloat(2, 15, 400),
        ];
    }
}