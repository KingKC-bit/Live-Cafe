<?php

namespace Database\Factories;

use App\Models\Partnership;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Partnership>
 */
class PartnershipFactory extends Factory
{
    protected $model = Partnership::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),

            'description' => fake()->sentence(),

            'status' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => [
            'status' => false,
        ]);
    }
}
