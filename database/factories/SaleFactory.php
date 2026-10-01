<?php

namespace Database\Factories;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sale>
 */
class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->staff(),

            'status' => fake()->randomElement([
                'pending',
                'confirmed',
                'collected',
                'cancelled',
            ]),

            'payment_method' => fake()->randomElement([
                'cash',
                'card',
            ]),

            'total' => fake()->randomFloat(2, 10, 800),

            'is_offline' => false,

            'synced_at' => null,

            'occurred_at' => fake()->dateTimeBetween(
                '-7 days',
                'now'
            ),
        ];
    }

    public function offline(): static
    {
        return $this->state(fn () => [
            'is_offline' => true,
            'synced_at' => now(),
        ]);
    }

    public function confirmed(): static
    {
        return $this->state(fn () => [
            'status' => 'confirmed',
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn () => [
            'status' => 'cancelled',
        ]);
    }
}
