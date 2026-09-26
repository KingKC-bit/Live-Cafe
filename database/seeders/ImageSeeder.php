<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ImageSeeder extends Seeder
{
    public function run(): void
    {
        Product::query()->each(function (Product $product): void {
            Image::factory()
                ->forProduct($product)
                ->create([
                    'path' => 'products/' . Str::slug($product->name) . '.jpg',
                    'alt_text' => $product->name,
                    'sort_order' => 0,
                ]);
        });
    }
}