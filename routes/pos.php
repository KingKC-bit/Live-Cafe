<?php

use App\Http\Controllers\Pos\PosController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| POS Routes
|--------------------------------------------------------------------------
| Staff and admin can access the POS.
| The sync endpoint accepts offline sales that were queued in the browser
| and must be revalidated before being committed to the database.
|--------------------------------------------------------------------------
*/

Route::prefix('pos')
    ->name('pos.')
    ->middleware(['auth', 'verified', 'role:staff,admin'])
    ->group(function () {

        // POS terminal interface
        Route::get('/', [PosController::class, 'index'])->name('index');

        // Process a new sale
        Route::post('/sale', [PosController::class, 'store'])->name('sale.store');

        // Sales review page — POS sales + online orders pending collection
        Route::get('/sales', [PosController::class, 'sales'])->name('sales.index');
        Route::get('/sales/{sale}', [PosController::class, 'show'])->name('sales.show');

        // Mark an online order as collected at the counter
        Route::patch('/orders/{order}/collect', [PosController::class, 'collectOrder'])->name('orders.collect');

        // Order ID lookup via AJAX
        Route::get('/orders/{order}/lookup', [PosController::class, 'lookupOrder'])->name('orders.lookup');

        // Offline sync
        Route::post('/sync', [PosController::class, 'sync'])->name('sync');
    });
