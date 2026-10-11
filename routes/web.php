<?php

<<<<<<< HEAD
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

require __DIR__.'/settings.php';
require __DIR__.'/shop.php';
require __DIR__.'/running.php';
require __DIR__.'/admin.php';
require __DIR__.'/pos.php';
=======
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
>>>>>>> 15ff7d93549fad61a193a6918816624dddfac3b3
