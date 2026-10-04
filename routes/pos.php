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

        // Process a new sale (online)
        Route::post('/sale', [PosController::class, 'store'])->name('sale.store');

        // View sale history (staff's own sales, admin sees all via admin panel)
        Route::get('/sales', [PosController::class, 'sales'])->name('sales.index');
        Route::get('/sales/{sale}', [PosController::class, 'show'])->name('sales.show');

        // Offline sync — receives queued browser sales for revalidation
        Route::post('/sync', [PosController::class, 'sync'])->name('sync');
    });
