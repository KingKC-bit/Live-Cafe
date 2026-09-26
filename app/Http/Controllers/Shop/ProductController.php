<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::orderBy('sort_order')->get();

        // Group available products by category name for the shop page
        $productsByCategory = Product::where('prod_availability', true)
            ->where('quantity', '>', 0)
            ->with('category')
            ->when(
                $request->category,
                fn ($q) => $q->where('category_id', $request->category)
            )
            ->get()
            ->groupBy(fn ($p) => $p->category->name);

        return view('shop.index', compact('categories', 'productsByCategory'));
    }

    public function show(Product $product): View
    {
        // Placeholder — build the product detail view next
        abort(404, 'Product detail page not yet built.');
    }
}