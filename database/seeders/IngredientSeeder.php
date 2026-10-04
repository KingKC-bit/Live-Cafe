<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $ingredients = [
            [
                'name' => 'Espresso Beans',
                'quantity' => 10.000,
                'availability' => true,
            ],
            [
                'name' => 'Whole Milk',
                'quantity' => 20.000,
                'availability' => true,
            ],
            [
                'name' => 'Oat Milk',
                'quantity' => 8.000,
                'availability' => true,
            ],
            [
                'name' => 'Drinking Water',
                'quantity' => 100.000,
                'availability' => true,
            ],
            [
                'name' => 'Chicken Breast',
                'quantity' => 8.000,
                'availability' => true,
            ],
            [
                'name' => 'Tortilla Wrap',
                'quantity' => 40.000,
                'availability' => true,
            ],
            [
                'name' => 'Lettuce',
                'quantity' => 5.000,
                'availability' => true,
            ],
            [
                'name' => 'Tomato',
                'quantity' => 5.000,
                'availability' => true,
            ],
            [
                'name' => 'Avocado',
                'quantity' => 6.000,
                'availability' => true,
            ],
            [
                'name' => 'Bread',
                'quantity' => 50.000,
                'availability' => true,
            ],
            [
                'name' => 'Bacon',
                'quantity' => 4.000,
                'availability' => true,
            ],
            [
                'name' => 'Eggs',
                'quantity' => 80.000,
                'availability' => true,
            ],
            [
                'name' => 'Blueberries',
                'quantity' => 3.000,
                'availability' => true,
            ],
            [
                'name' => 'Flour',
                'quantity' => 20.000,
                'availability' => true,
            ],
            [
                'name' => 'Butter',
                'quantity' => 8.000,
                'availability' => true,
            ],
            [
                'name' => 'Yoghurt',
                'quantity' => 12.000,
                'availability' => true,
            ],
            [
                'name' => 'Granola',
                'quantity' => 6.000,
                'availability' => true,
            ],
            [
                'name' => 'Bananas',
                'quantity' => 10.000,
                'availability' => true,
            ],
            [
                'name' => 'Fresh Oranges',
                'quantity' => 60.000,
                'availability' => true,
            ],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }
    }
}
