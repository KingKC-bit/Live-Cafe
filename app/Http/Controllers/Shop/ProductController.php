<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        // Quick-jump buttons, in our chosen order: Food, Drink, Add On, Merch
        $categories = Category::orderBy('name')
            ->get()
            ->sortBy(fn (Category $c) => $this->categoryRank($c->name))
            ->values();

        // All products grouped by category, in the same order.
        // Available products come first, sold-out ones last.
        $productsByCategory = Product::with(['category', 'images'])
            ->orderByRaw('(prod_availability = 0 OR quantity < 1) ASC')
            ->orderBy('name')
            ->get()
            ->filter(fn (Product $p) => $p->category !== null)
            ->groupBy(fn (Product $p) => $p->category->name) // @phpstan-ignore-line
            ->sortBy(fn ($products, $name) => $this->categoryRank((string) $name));

        return view('shop.index', compact('categories', 'productsByCategory'));
    }

        public function show(Product $product): View|RedirectResponse
    {
        // Out-of-stock products have no details page.
        if (! $product->prod_availability || $product->quantity < 1) {
            return redirect()
                ->route('shop.index')
                ->with('error', $product->name.' is currently out of stock.');
        }

        $product->load(['category', 'images']);

        return view('shop.show', compact('product'));
    }

    /**
     * Position of a category on the page (lower = higher up).
     */
    private function categoryRank(string $name): int
    {
        $name = strtolower($name);

        return match (true) {
            str_contains($name, 'food') => 0,
            str_contains($name, 'drink') => 1,
            str_contains($name, 'add') => 2,
            str_contains($name, 'merch') => 3,
            default => 9,
        };
    }
}