<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Image>
 */
class ImageFactory extends Factory
{
    protected $model = Image::class;

    public function definition(): array
    {
        return [
            'imageable_id' => Product::factory(),

            'imageable_type' => Product::class,

            'path' => 'products/'.fake()->unique()->slug().'.jpg',

            'alt_text' => fake()->sentence(5),

            'sort_order' => 0,
        ];
    }

    public function forProduct(Product $product): static
    {
        return $this->state(fn () => [
            'imageable_id' => $product->id,
            'imageable_type' => Product::class,
        ]);
    }
}
