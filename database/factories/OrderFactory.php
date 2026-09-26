<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->customer(),

            'status' => fake()->randomElement([
                'pending',
                'confirmed',
                'collected',
                'cancelled',
            ]),

            'total' => fake()->randomFloat(2, 30, 800),

            'collection_time' => fake()->dateTimeBetween(
                'now',
                '+7 days'
            ),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => [
            'status' => 'pending',
        ]);
    }

    public function confirmed(): static
    {
        return $this->state(fn () => [
            'status' => 'confirmed',
        ]);
    }

    public function collected(): static
    {
        return $this->state(fn () => [
            'status' => 'collected',
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn () => [
            'status' => 'cancelled',
        ]);
    }
}