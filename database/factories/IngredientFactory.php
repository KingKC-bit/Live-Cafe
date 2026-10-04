<?php

namespace Database\Factories;

use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ingredient>
 */
class IngredientFactory extends Factory
{
    protected $model = Ingredient::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),

            'quantity' => fake()->randomFloat(3, 1, 100),

            'availability' => true,
        ];
    }

    public function unavailable(): static
    {
        return $this->state(fn () => [
            'availability' => false,
        ]);
    }
}
