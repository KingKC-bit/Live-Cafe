<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;

beforeEach(fn () => $this->actingAs(User::factory()->admin()->create()));

test('lets an admin add a category', function () {
    $this->post(route('admin.categories.store'), ['name' => 'Smoothies', 'sort_order' => 9])
        ->assertRedirect();

    expect(Category::where('name', 'Smoothies')->exists())->toBeTrue();
});

test('deactivates instead of deleting a category that has products', function () {
    $product = Product::factory()->create();

    $this->delete(route('admin.categories.destroy', $product->category_id));
    expect(Category::find($product->category_id))->not->toBeNull();

    $this->put(route('admin.categories.update', $product->category_id), [
        'name' => 'Old merch', 'sort_order' => 5, 'is_active' => 0,
    ]);
    expect(Category::find($product->category_id)->is_active)->toBeFalse();
});
