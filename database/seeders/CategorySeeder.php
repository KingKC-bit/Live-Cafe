<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Food',
                'sort_order' => 1,
            ],
            [
                'name' => 'Drink',
                'sort_order' => 2,
            ],
            [
                'name' => 'Merch',
                'sort_order' => 3,
            ],
            [
                'name' => 'Add On',
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
