<?php

use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\OrderController;
use App\Http\Controllers\Shop\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Shop Routes
|--------------------------------------------------------------------------
| Public product browsing is available to all visitors.
| Cart, checkout, and orders require authentication.
|--------------------------------------------------------------------------
*/

Route::prefix('shop')->name('shop.')->group(function () {

    // Public — product browsing (no login required)
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.show');
    
    // Authenticated customers only
    Route::middleware(['auth', 'verified'])->group(function () {

        // Cart
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
        Route::patch('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
        Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

        // Checkout
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

        // Orders
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::delete('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    });
});