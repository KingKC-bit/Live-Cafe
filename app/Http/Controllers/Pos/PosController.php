<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\View\View;

class PosController extends Controller
{
    public function index(): View
    {
        $products = Product::where('prod_availability', true)
            ->where('quantity', '>', 0)
            ->with('category')
            ->orderBy('name')
            ->get();

        return view('pos.index', compact('products'));
    }

    public function store(): void {}

    public function sales(): View
    {
        $sales = Sale::with('user')
            ->where('user_id', auth()->user()->id)
            ->latest('occurred_at')
            ->get();

        return view('pos.index', compact('sales'));
    }

    public function show(Sale $sale): View
    {
        return view('pos.index', compact('sale'));
    }

    public function sync(): void {}
}
