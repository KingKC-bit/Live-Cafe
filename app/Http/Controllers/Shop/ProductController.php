<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        // 1. Get all categories EXCEPT Add-ons, ordered alphabetically by name
        $categories = Category::where('name', 'not like', '%add-on%')
            ->where('name', 'not like', '%addon%')
            ->orderBy('name') // Sorts alphabetically (Drinks, Food, Merch)
            ->get();

        // 2. Load products, filtering out the Add-ons category completely
        $productsByCategory = Product::where('prod_availability', true)
            ->where('quantity', '>', 0)
            ->with('category')
            ->get()
            ->filter(fn ($p) => $p->category !== null && ! Str::contains(strtolower($p->category->name), ['add-on', 'addon']))
            ->groupBy(fn ($p) => $p->category->name);

        return view('shop.index', compact('categories', 'productsByCategory'));
    }

    public function show(Product $product): View
    {
        $product->load('category');

        return view('shop.show', compact('product'));
    }
}
