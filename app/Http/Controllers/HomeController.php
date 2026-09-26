<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Grab a handful of available products for the home page strip
        $featuredProducts = Product::where('prod_availability', true)
            ->where('quantity', '>', 0)
            ->with('category')
            ->latest()
            ->take(6)
            ->get();

        // Upcoming events only — ordered by soonest first
        $upcomingEvents = Event::where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date')
            ->orderBy('event_time')
            ->take(4)
            ->get();

        return view('home', compact('featuredProducts', 'upcomingEvents'));
    }
}