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

        $productsByCategory = Product::where('prod_availability', true)
        ->where('quantity', '>', 0)
        ->with('category')  // must be before get()
        ->get()
        ->filter(fn ($p) => $p->category !== null) // safety — skip orphaned products
        ->groupBy(fn ($p) => $p->category->name);

        return view('shop.index', compact('categories', 'productsByCategory'));
    }

    public function show(Product $product): View
    {
        $product->load('category');
        return view('shop.show', compact('product'));
    }
}