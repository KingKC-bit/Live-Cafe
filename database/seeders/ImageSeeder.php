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
        $availableImages = [
            'chicken-wrap',
            'cappuccino',
            'blueberry-muffin',
            'latte',
        ];

        Product::query()->each(function (Product $product) use ($availableImages): void {
            $slug = Str::slug($product->name);

            if (! in_array($slug, $availableImages, true)) {
                return;
            }

            Image::factory()
                ->forProduct($product)
                ->create([
                    'path' => 'images/'.$slug.'.jpg',
                    'alt_text' => $product->name,
                    'sort_order' => 0,
                ]);
        });
    }
}
