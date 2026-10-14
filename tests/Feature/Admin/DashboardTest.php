<?php

use App\Models\Ingredient;
use App\Models\Product;
use App\Models\User;

test('counts a made item as available when its ingredients are in stock', function () {
    $wrap = Product::factory()->create(['quantity' => 0, 'prod_availability' => true]);   // 0 is only the placeholder
    $wrap->ingredients()->attach(Ingredient::factory()->create(['quantity' => 5]), ['quantity_required' => 1]);

    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertViewHas('stats', fn (array $stats) => $stats['availableProducts'] === 1);
});
