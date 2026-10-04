<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Seeder;
use RuntimeException;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'category' => 'Food',
                'name' => 'Chicken Wrap',
                'price' => 65.00,
                'quantity' => 30,
                'description' => 'Grilled chicken wrap with lettuce, tomato and avocado.',
                'prod_availability' => true,
                'ingredients' => [
                    'Chicken Breast' => 0.150,
                    'Tortilla Wrap' => 1.000,
                    'Lettuce' => 0.030,
                    'Tomato' => 0.030,
                    'Avocado' => 0.050,
                ],
            ],
            [
                'category' => 'Food',
                'name' => 'Avocado Toast',
                'price' => 55.00,
                'quantity' => 25,
                'description' => 'Toasted bread topped with fresh avocado.',
                'prod_availability' => true,
                'ingredients' => [
                    'Bread' => 2.000,
                    'Avocado' => 0.100,
                ],
            ],
            [
                'category' => 'Food',
                'name' => 'Bacon and Egg Roll',
                'price' => 48.00,
                'quantity' => 20,
                'description' => 'Fresh breakfast roll with bacon and scrambled eggs.',
                'prod_availability' => true,
                'ingredients' => [
                    'Bread' => 1.000,
                    'Bacon' => 0.050,
                    'Eggs' => 2.000,
                ],
            ],
            [
                'category' => 'Food',
                'name' => 'Blueberry Muffin',
                'price' => 32.00,
                'quantity' => 30,
                'description' => 'Freshly baked blueberry muffin.',
                'prod_availability' => true,
                'ingredients' => [
                    'Flour' => 0.080,
                    'Blueberries' => 0.050,
                    'Butter' => 0.020,
                    'Eggs' => 1.000,
                ],
            ],
            [
                'category' => 'Food',
                'name' => 'Butter Croissant',
                'price' => 28.00,
                'quantity' => 30,
                'description' => 'Fresh buttery croissant.',
                'prod_availability' => true,
                'ingredients' => [
                    'Flour' => 0.080,
                    'Butter' => 0.030,
                ],
            ],
            [
                'category' => 'Food',
                'name' => 'Granola Yoghurt Bowl',
                'price' => 52.00,
                'quantity' => 20,
                'description' => 'Yoghurt bowl with granola and fresh banana.',
                'prod_availability' => true,
                'ingredients' => [
                    'Yoghurt' => 0.200,
                    'Granola' => 0.060,
                    'Bananas' => 0.500,
                ],
            ],
            [
                'category' => 'Drink',
                'name' => 'Americano',
                'price' => 30.00,
                'quantity' => 40,
                'description' => 'Espresso with hot water.',
                'prod_availability' => true,
                'ingredients' => [
                    'Espresso Beans' => 0.018,
                    'Drinking Water' => 0.250,
                ],
            ],
            [
                'category' => 'Drink',
                'name' => 'Cappuccino',
                'price' => 38.00,
                'quantity' => 35,
                'description' => 'Espresso with steamed milk and foam.',
                'prod_availability' => true,
                'ingredients' => [
                    'Espresso Beans' => 0.018,
                    'Whole Milk' => 0.180,
                ],
            ],
            [
                'category' => 'Drink',
                'name' => 'Latte',
                'price' => 40.00,
                'quantity' => 35,
                'description' => 'Espresso with smooth steamed milk.',
                'prod_availability' => true,
                'ingredients' => [
                    'Espresso Beans' => 0.018,
                    'Whole Milk' => 0.250,
                ],
            ],
            [
                'category' => 'Drink',
                'name' => 'Iced Coffee',
                'price' => 45.00,
                'quantity' => 30,
                'description' => 'Cold coffee served with milk.',
                'prod_availability' => true,
                'ingredients' => [
                    'Espresso Beans' => 0.018,
                    'Whole Milk' => 0.200,
                    'Drinking Water' => 0.100,
                ],
            ],
            [
                'category' => 'Drink',
                'name' => 'Fresh Orange Juice',
                'price' => 42.00,
                'quantity' => 25,
                'description' => 'Freshly squeezed orange juice.',
                'prod_availability' => true,
                'ingredients' => [
                    'Fresh Oranges' => 4.000,
                ],
            ],
            [
                'category' => 'Drink',
                'name' => 'Bottled Water',
                'price' => 18.00,
                'quantity' => 40,
                'description' => 'Chilled bottled water.',
                'prod_availability' => true,
                'ingredients' => [],
            ],
            [
                'category' => 'Merch',
                'name' => 'Live Cafe Running Club T Shirt',
                'price' => 350.00,
                'quantity' => 15,
                'description' => 'Official Live Cafe running club T shirt.',
                'prod_availability' => true,
                'ingredients' => [],
            ],
            [
                'category' => 'Merch',
                'name' => 'Live Cafe Running Shorts',
                'price' => 280.00,
                'quantity' => 12,
                'description' => 'Official Live Cafe running club running shorts.',
                'prod_availability' => true,
                'ingredients' => [],
            ],
            [
                'category' => 'Merch',
                'name' => 'Live Cafe Running Cap',
                'price' => 180.00,
                'quantity' => 20,
                'description' => 'Official Live Cafe running club cap.',
                'prod_availability' => true,
                'ingredients' => [],
            ],
            [
                'category' => 'Add On',
                'name' => 'Extra Espresso Shot',
                'price' => 10.00,
                'quantity' => 40,
                'description' => 'Add an extra espresso shot.',
                'prod_availability' => true,
                'ingredients' => [
                    'Espresso Beans' => 0.018,
                ],
            ],
            [
                'category' => 'Add On',
                'name' => 'Oat Milk Upgrade',
                'price' => 8.00,
                'quantity' => 0,
                'description' => 'Replace regular milk with oat milk.',
                'prod_availability' => false,
                'ingredients' => [
                    'Oat Milk' => 0.250,
                ],
            ],
        ];

        foreach ($products as $data) {
            $category = Category::where('name', $data['category'])->first();

            if (! $category) {
                throw new RuntimeException(
                    "Category [{$data['category']}] not found."
                );
            }

            $product = Product::create([
                'name' => $data['name'],
                'price' => $data['price'],
                'category_id' => $category->id,
                'description' => $data['description'],
                'prod_availability' => $data['prod_availability'],
                'quantity' => $data['quantity'],
            ]);

            foreach ($data['ingredients'] as $ingredientName => $quantityRequired) {
                $ingredient = Ingredient::where('name', $ingredientName)->first();

                if (! $ingredient) {
                    throw new RuntimeException(
                        "Ingredient [{$ingredientName}] not found."
                    );
                }

                $product->ingredients()->attach(
                    $ingredient->id,
                    [
                        'quantity_required' => $quantityRequired,
                    ]
                );
            }
        }
    }
}
